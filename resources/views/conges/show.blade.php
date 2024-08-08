<!-- resources/views/conges/show.blade.php -->
@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Détails de la Demande de Congé</h1>

    <div class="card">
        <div class="card-header">
            Détails du Congé
        </div>
        <div class="card-body">
            <p><strong>Employé:</strong> {{ $conge->employe->nom }} {{ $conge->employe->prenom }}</p>
            <p><strong>Type de Congé:</strong> {{ $conge->typeConges }}</p>
            <p><strong>Date de Début:</strong> {{ $conge->dateDebut }}</p>
            <p><strong>Date de Fin:</strong> {{ $conge->dateFin }}</p>
            <p><strong>Status:</strong> {{ ucfirst($conge->status) }}</p>
            <p><strong>Commentaire:</strong> {{ $conge->commentaire }}</p>
            
            @if($conge->status == 'en attente')
                @if(auth()->user()->profil == 'manager')
                    <a href="{{ route('conges.approveByManager', $conge->id) }}" class="btn btn-success">Approuver</a>
                    <a href="{{ route('conges.reject', $conge->id) }}" class="btn btn-danger">Refuser</a>
                @elseif(auth()->user()->profil == 'responsables RH')
                    <a href="{{ route('conges.approveByRh', $conge->id) }}" class="btn btn-success">Approuver</a>
                    <a href="{{ route('conges.reject', $conge->id) }}" class="btn btn-danger">Refuser</a>
                @endif
            @endif
            
            <a href="{{ route('conges.index') }}" class="btn btn-primary mt-3">Retour à la Liste</a>
        </div>
    </div>
</div>
@endsection
