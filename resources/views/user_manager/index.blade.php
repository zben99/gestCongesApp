@extends('layouts.template')

@section('css')
    <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="btn btn-custom-blue btn-block">
            <h2 class="card-title">Administration | Workflow</h2>
        </div>
        <div class="card-body">
            <!-- Formulaire de recherche -->
            <form action="{{ route('user-manager.index') }}" method="GET" class="mb-3">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher par matricule, nom, prénom..." value="{{ request()->get('search') }}">
                </div>
                <button type="submit" class="btn btn-custom-blue btn-block">Rechercher</button>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Profil</th>
                        <th>Manager Actuel</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>{{ $employee->matricule }}</td>
                            <td>{{ $employee->nom }}</td>
                            <td>{{ $employee->prenom }}</td>
                            <td>{{ $employee->profil }}</td>
                            <td>
                                @if($employee->managers->isNotEmpty())
                                    {{ $employee->managers->first()->nom }} {{ $employee->managers->first()->prenom }}
                                @else
                                    Aucun manager assigné
                                @endif
                            </td>
                            <td>
                                <!-- Bouton pour assigner un manager -->
                                <a href="{{ route('user-manager.assign-form', $employee->id) }}" class="btn btn-custom-blue btn-block">Assigner Manager</a>
                                <!-- Bouton pour changer le manager -->
                                <a href="{{ route('user-manager.change-form', $employee->id) }}" class="btn btn-warning">Changer Manager</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" >Aucun employé trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
