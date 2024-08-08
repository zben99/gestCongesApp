@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Ajouter une Demande de Congé</h1>
    <form action="{{ route('conges.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="employeId">Employé:</label>
            <select id="employeId" name="employeId" class="form-control" required>
                @foreach ($employes as $employe)
                    <option value="{{ $employe->id }}">{{ $employe->nom }} {{ $employe->prenom }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="typeConges">Type de Congé:</label>
            <select id="typeConges" name="typeConges" class="form-control" required>
                <option value="annuels">Annuel</option>
                <option value="maladie">Maladie</option>
                <option value="maternité">Maternité</option>
                <option value="paternité">Paternité</option>
            </select>
        </div>
        <div class="form-group">
            <label for="dateDebut">Date de Début:</label>
            <input type="date" id="dateDebut" name="dateDebut" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="dateFin">Date de Fin:</label>
            <input type="date" id="dateFin" name="dateFin" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="commentaire">Commentaire:</label>
            <textarea id="commentaire" name="commentaire" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Soumettre</button>
    </form>
</div>
@endsection
