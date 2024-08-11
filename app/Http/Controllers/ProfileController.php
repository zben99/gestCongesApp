<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

   // Recherche de l'utilisateur à mettre à jour
   $user = User::findOrFail($admin);

   // Validation des données
   $validator = Validator::make($request->all(), [
       'nom' => 'required|string|max:255',
       'prenom' => 'required|string|max:255',
       'matricule' => [
           'nullable',
           'string',
           'max:191',
           Rule::unique('users')->ignore($user->id),
       ],
       'email' => [
           'required',
           'string',
           'email',
           'max:191',
           Rule::unique('users')->ignore($user->id),
       ],
       'password' => 'nullable|string|min:8|confirmed',
       'telephone1' => 'required|string|max:191',
       'telephone2' => 'nullable|string|max:191',
       'birthdate' => 'nullable|date',
   ]);

   // Si la validation échoue
   if ($validator->fails()) {
       return redirect()->back()->withErrors($validator)->withInput();
   }


   // Mise à jour de l'utilisateur
   $user->nom = $request->input('nom');
   $user->prenom = $request->input('prenom');
   $user->matricule = $request->input('matricule');
   $user->email = $request->input('email');

   // Mise à jour du mot de passe uniquement si un nouveau est fourni
   if ($request->filled('password')) {
       $user->password = Hash::make($request->input('password'));
   }

   $user->telephone1 = $request->input('telephone1');
   $user->telephone2 = $request->input('telephone2');
   $user->birth_date = $request->input('birthDate');

   $user->save();

   // Redirection après succès
   return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès');


    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
