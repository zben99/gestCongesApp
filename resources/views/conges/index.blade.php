<!-- resources/views/conges/index.blade.php -->
@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h1>Liste des Congés</h1>
    <a href="{{ route('conges.create') }}" class="btn btn-primary">Ajouter une Demande de Congé</a>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Employé</th>
                <th>Type de Congé</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($conges as $conge)
                <tr class="{{ $conge->status == 'approuvé' ? 'bg-success' : ($conge->status == 'refusé' ? 'bg-danger' : '') }}">
                    <td>{{ $conge->employe->nom }} {{ $conge->employe->prenom }}</td>
                    <td>{{ $conge->typeConges }}</td>
                    <td>{{ $conge->dateDebut }}</td>
                    <td>{{ $conge->dateFin }}</td>
                    <td>{{ ucfirst($conge->status) }}</td>
                    <td>
                        @if($conge->status == 'en attente')
                            @if(auth()->user()->profil == 'manager')
                                <a href="{{ route('conges.approveByManager', $conge->id) }}" class="btn btn-success btn-sm">Approuver</a>
                                <a href="{{ route('conges.reject', $conge->id) }}" class="btn btn-danger btn-sm">Refuser</a>
                            @endif
                        @elseif($conge->status == 'en attente RH')
                            @if(auth()->user()->profil == 'responsables RH')
                                <a href="{{ route('conges.approveByRh', $conge->id) }}" class="btn btn-success btn-sm">Approuver</a>
                                <a href="{{ route('conges.reject', $conge->id) }}" class="btn btn-danger btn-sm">Refuser</a>
                            @endif
                        @endif
                        <a href="{{ route('conges.show', $conge->id) }}" class="btn btn-info btn-sm">Voir</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
