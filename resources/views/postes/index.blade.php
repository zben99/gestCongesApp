
@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Liste des Postes</h1>
    <a href="{{ route('postes.create') }}" class="btn btn-primary">Ajouter un Poste</a>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($postes as $poste)
                <tr>
                    <td>{{ $poste->name_poste }}</td>
                    <td>{{ $poste->description }}</td>
                    <td>
                        <a href="{{ route('postes.edit', $poste->id) }}" class="btn btn-warning">Modifier</a>
                        <form action="{{ route('postes.destroy', $poste->id) }}" method="POST" style="display:inline;">
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
