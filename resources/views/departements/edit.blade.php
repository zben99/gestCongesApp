

@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Modifier le Département</h1>
    <form action="{{ route('departements.update', $departement->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name_departement">Nom:</label>
            <input type="text" id="name_departement" name="name_departement" class="form-control" value="{{ $departement->name_departement }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control">{{ $departement->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
    </form>
</div>
@endsection
