
@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Liste des Départements</h1>
    <a href="{{ route('departements.create') }}" class="btn btn-primary">Ajouter un Département</a>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($departements as $departement)
                <tr>
                    <td>{{ $departement->name_departement }}</td>
                    <td>{{ $departement->description }}</td>
                    <td>
                        <a href="{{ route('departements.edit', $departement->id) }}" class="btn btn-warning">Modifier</a>
                        <form action="{{ route('departements.destroy', $departement->id) }}" method="POST" style="display:inline;">
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
@endsection
