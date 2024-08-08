<!-- resources/views/backend/employes/edit.blade.php -->
@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Modifier un Employé</h1>
    <form action="{{ route('employes.update', $employe->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" class="form-control" value="{{ $employe->nom }}" required>
        </div>
        
        <div class="form-group">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" class="form-control" value="{{ $employe->prenom }}" required>
        </div>
        
        <div class="form-group">
            <label for="matricule">Matricule:</label>
            <input type="text" id="matricule" name="matricule" class="form-control" value="{{ $employe->matricule }}" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $employe->email }}" required>
        </div>
        
        <div class="form-group">
            <label for="telephone1">Téléphone 1:</label>
            <input type="text" id="telephone1" name="telephone1" class="form-control" value="{{ $employe->telephone1 }}" required>
        </div>
        
        <div class="form-group">
            <label for="telephone2">Téléphone 2:</label>
            <input type="text" id="telephone2" name="telephone2" class="form-control" value="{{ $employe->telephone2 }}">
        </div>
        
        <div class="form-group">
            <label for="dateNaissance">Date de Naissance:</label>
            <input type="date" id="dateNaissance" name="dateNaissance" class="form-control" value="{{ $employe->dateNaissance }}" required>
        </div>
        
        <div class="form-group">
            <label for="profil">Profil:</label>
            <select id="profil" name="profil" class="form-control" required>
                <option value="employés" {{ $employe->profil == 'employés' ? 'selected' : '' }}>Employés</option>
                <option value="manager" {{ $employe->profil == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="responsables RH" {{ $employe->profil == 'responsables RH' ? 'selected' : '' }}>Responsables RH</option>
                <option value="administrateurs" {{ $employe->profil == 'administrateurs' ? 'selected' : '' }}>Administrateurs</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="departementId">Département:</label>
            <select id="departementId" name="departementId" class="form-control" required>
                @foreach ($departements as $departement)
                    <option value="{{ $departement->id }}" {{ $employe->departementId == $departement->id ? 'selected' : '' }}>
                        {{ $departement->name_departement }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="posteId">Poste:</label>
            <select id="posteId" name="posteId" class="form-control" required>
                @foreach ($postes as $poste)
                    <option value="{{ $poste->id }}" {{ $employe->posteId == $poste->id ? 'selected' : '' }}>
                        {{ $poste->name_poste }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="dateArrive">Date d'Arrivée:</label>
            <input type="date" id="dateArrive" name="dateArrive" class="form-control" value="{{ $employe->dateArrive }}" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
