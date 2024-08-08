
@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Ajouter un Département</h1>
    <form action="{{ route('departements.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name_departement">Nom:</label>
            <input type="text" id="name_departement" name="name_departement" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Ajouter</button>
    </form>
</div>
@endsection
