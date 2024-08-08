<!-- resources/views/backend/employes/index.blade.php -->
@extends('layouts.template')
@section('content')
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Employés</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Liste des Employés</h1>
        <a href="{{ route('employes.create') }}" class="btn btn-primary">Ajouter un Employé</a>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Matricule</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employes as $employe)
                    <tr>
                        <td>{{ $employe->nom }}</td>
                        <td>{{ $employe->prenom }}</td>
                        <td>{{ $employe->matricule }}</td>
                        <td>{{ $employe->email }}</td>
                        <td>
                            <a href="{{ route('employes.edit', $employe->id) }}" class="btn btn-warning">Modifier</a>
                            <form action="{{ route('employes.destroy', $employe->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
@endsection
