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
 * Met à jour les informations de profil de l'utilisateur.
 */
public function update(ProfileUpdateRequest $request)
{
     dd($request->all());
    // Recherche de l'utilisateur à mettre à jour
    $user = auth()->user();

    // Validation des données
    $validator = Validator::make($request->all(), [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => [
            'required',
            'string',
            'email',
            'max:191',
            Rule::unique('users')->ignore($user->id),
        ],
        'telephone1' => 'required|string|max:191',
        'telephone2' => 'nullable|string|max:191',
        'birth_date' => 'nullable|date',
    ]);

    // Si la validation échoue
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Mise à jour de l'utilisateur
    $user->nom = $request->input('nom');
    $user->prenom = $request->input('prenom');
    $user->email = $request->input('email');
    $user->telephone1 = $request->input('telephone1');
    $user->telephone2 = $request->input('telephone2');
    $user->birth_date = $request->input('birth_date');

    // Sauvegarde des modifications
    $user->save();

    // Redirection après succès
    return redirect()->route('profile.edit')->with('status', 'profile-updated');

}

/**
 * Met à jour le mot de passe de l'utilisateur.
 */
public function updatePassword(Request $request): RedirectResponse
{
    $user = auth()->user();

    // Validation des données
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Vérification du mot de passe actuel
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
    }

    // Mise à jour du mot de passe
    $user->password = Hash::make($request->password);
    $user->save();

    // Redirection après succès
    return redirect()->route('profile.edit')->with('status', 'password-updated');
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
