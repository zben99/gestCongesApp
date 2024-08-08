
@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Ajouter un Poste</h1>
    <form action="{{ route('postes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name_poste">Nom:</label>
            <input type="text" id="name_poste" name="name_poste" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Ajouter</button>
    </form>
</div>
@endsection
