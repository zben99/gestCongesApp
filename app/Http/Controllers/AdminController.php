<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poste;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;



class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Par défaut, 10 utilisateurs par page
    $perPage = $request->input('per_page', 4);

    // Recherche
    $search = $request->input('search');

    // Construire la requête de base pour récupérer les utilisateurs
    $query = User::query();

    // Appliquer le filtre de recherche si nécessaire
    if ($search) {
        $query->where('nom', 'like', "%{$search}%")
              ->orWhere('prenom', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('matricule', 'like', "%{$search}%");
    }

    // Récupérer les utilisateurs avec pagination
    $users = $query->paginate($perPage);

    // Passer les variables à la vue
    return view("administrateurs.index", compact("users", "perPage", "search"));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $departements = Departement::all();
        $postes = Poste::all();


        return view('administrateurs.edit', compact('departements', 'postes'));
    }

    public function liste_conges()
    {
        // Récupérer tous les utilisateurs
        $users = User::all();

        // Appliquer la transformation et le filtrage
        $users = $users->transform(function($user) {
            // Calculer le nombre de jours depuis l'arrivée
            $days1 = (new \DateTime(now()))->diff(new \DateTime($user->arrival_date))->days + 1;

            // Appliquer la condition : ne conserver que les utilisateurs avec plus de 360 jours
            if ($days1 >= 360) {
                $days = (new \DateTime(now()))->diff(new \DateTime($user->initialization_date))->days + 1;
                $nbreConge = ($days * 2.5) / 30;

                // Calculer les congés restants
                $user->congeRestant = floor(($nbreConge + $user->initial + $user->joursBonus) - $user->pris);
                $user->days1 = $days1;

                return $user;
            }

            // Retourne null pour les utilisateurs qui ne remplissent pas la condition
            return null;
        })->filter()->sortByDesc('congeRestant'); // Utiliser filter() pour supprimer les entrées nulles

        // Appliquer la pagination manuellement après transformation
        $perPage = 10; // Nombre d'éléments par page
        $page = request()->get('page', 1); // Récupérer la page actuelle à partir de la requête
        $pagedUsers = $users->slice(($page - 1) * $perPage, $perPage)->values(); // Découper la collection
        $users = new \Illuminate\Pagination\LengthAwarePaginator($pagedUsers, $users->count(), $perPage, $page, [
            'path' => request()->url(), // Pour conserver les paramètres de l'URL dans la pagination
            'query' => request()->query(),
        ]);

    //dd($users);
        return view('conges.liste_users_conge', compact('users'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'nullable|string|max:191|unique:users',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telephone1' => 'required|string|max:191',
            'telephone2' => 'nullable|string|max:191',
            'birthDate' => 'nullable|date',
            'profil' => 'required|in:employés,manager,responsables RH,administrateurs',
            'departementId' => 'nullable|exists:departements,id',
            'posteId' => 'nullable|exists:postes,id',
            'arrivalDate' => 'nullable|date',
            'initialization_date' => 'nullable|date',
            'initial' => 'nullable|integer|min:0',

        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }



        // Création de l'utilisateur
        $user = new User();
        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->matricule = $request->input('matricule');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->telephone1 = $request->input('telephone1');
        $user->telephone2 = $request->input('telephone2');
        $user->birth_date = $request->input('birthDate');
        $user->profil = $request->input('profil');
        $user->departementId = $request->input('departementId');
        $user->posteId = $request->input('posteId');
        $user->arrival_date = $request->input('arrivalDate');
        $user->initialization_date = $request->input('initializationDate');
        $user->initial = $request->input('initial', 0);
        $user->save();


        // Redirection après succès
        return redirect()->route('admins.index')->with('success', 'Admin créé avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show($admin)
    {
        $user = User::findOrFail($admin);

        $departements = Departement::all();
        $postes = Poste::all();


        $days = (new \DateTime(now()))->diff(new \DateTime($user->initialization_date))->days + 1;
        $nbreConge=($days*2.5)/30;


        $congeRestant= floor($nbreConge+$user->initial+$user->joursBonus - $user->pris);



        return view("administrateurs.show", compact("user", "departements", "postes", 'congeRestant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $admin)
    {
        $user = User::findOrFail($admin);

        $departements = Departement::all();
        $postes = Poste::all();

        return view("administrateurs.edit", compact("user", "departements", "postes"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $admin)
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
            'profil' => 'required|in:employés,manager,responsables RH,administrateurs',
            'departementId' => 'nullable|exists:departements,id',
            'posteId' => 'nullable|exists:postes,id',
            'arrivalDate' => 'nullable|date',
            'initializationDate' => 'nullable|date',
            'initial' => 'nullable|integer|min:0',
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
        $user->profil = $request->input('profil');
        $user->departementId = $request->input('departementId');
        $user->posteId = $request->input('posteId');
        $user->arrival_date = $request->input('arrivalDate');
        $user->initialization_date = $request->input('initializationDate');
        $user->initial = $request->input('initial', 0);
        $user->save();

        // Redirection après succès
        return redirect()->route('admins.index')->with('success', 'Admin mis à jour avec succès');
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

    public function ajouterJoursBonus(Request $request, $admin)
    {
        $user = User::findOrFail($admin);

        $joursBonus = $request->input('joursBonus');

        // Logique pour ajouter les jours de congé bonus
        $user->joursBonus += $joursBonus;
        $user->save();

        return redirect()->back()->with('success', 'Jours de congé bonus ajoutés avec succès');
    }


}
