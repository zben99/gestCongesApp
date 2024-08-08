
@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Modifier le Poste</h1>
    <form action="{{ route('postes.update', $poste->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name_poste">Nom:</label>
            <input type="text" id="name_poste" name="name_poste" class="form-control" value="{{ $poste->name_poste }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control">{{ $poste->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
    </form>
</div>
@endsection
