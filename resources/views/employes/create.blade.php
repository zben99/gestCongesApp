@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Ajouter un Employé</h1>
    <form action="{{ route('employes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="matricule">Matricule:</label>
            <input type="text" id="matricule" name="matricule" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="telephone1">Téléphone 1:</label>
            <input type="text" id="telephone1" name="telephone1" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="telephone2">Téléphone 2:</label>
            <input type="text" id="telephone2" name="telephone2" class="form-control">
        </div>
        <div class="form-group">
            <label for="dateNaissance">Date de Naissance:</label>
            <input type="date" id="dateNaissance" name="dateNaissance" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="profil">Profil:</label>
            <select id="profil" name="profil" class="form-control" required>
                <option value="employés">Employés</option>
                <option value="manager">Manager</option>
                <option value="responsables RH">Responsables RH</option>
                <option value="administrateurs">Administrateurs</option>
            </select>
        </div>
        <div class="form-group">
            <label for="departementId">Département:</label>
            <select id="departementId" name="departementId" class="form-control" required>
                @foreach ($departements as $departement)
                    <option value="{{ $departement->id }}">{{ $departement->name_departement }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="posteId">Poste:</label>
            <select id="posteId" name="posteId" class="form-control" required>
                @foreach ($postes as $poste)
                    <option value="{{ $poste->id }}">{{ $poste->name_poste }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="dateArrive">Date d'Arrivée:</label>
            <input type="date" id="dateArrive" name="dateArrive" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
@endsection
