<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();


        return view("administrateurs.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("administrateurs.edit");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->route('admins.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        // Création de l'utilisateur
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Redirection après succès
        return redirect()->route('admins.index')->with('success', 'Admin créé avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $admin)
    {
        $user = User::findOrFail($admin);

        return view("administrateurs.edit", compact("user"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $admin)
    {
        $user = User::findOrFail($admin);
        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->route('admins.edit', $user)
                             ->withErrors($validator)
                             ->withInput();
        }

        // Création de l'utilisateur

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Redirection après succès
        return redirect()->route('admins.index')->with('success', 'Admin modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $admin)
    {


        $user = User::findOrFail($admin);
        if (!$user) {
            return redirect(route('admins.index'))->with('error', 'Utilisateur non trouvé');
        }

        $user->delete();

        return redirect(route('admins.index'))->with('success', 'Utilisateur supprimé avec succès');
    }

}
