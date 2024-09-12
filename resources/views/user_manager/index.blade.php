@extends('layouts.template')

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Liste des Employés</h2>
        </div>
        <div class="card-body">
            <!-- Formulaire de recherche -->
            <form action="{{ route('user-manager.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher par matricule, nom ou prénom" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                </div>
            </form>

            <!-- Tableau des employés -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Profil</th>
                        <th>Manager</th> <!-- Nouvelle colonne -->
                        <th>Responsable RH</th> <!-- Nouvelle colonne -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->id }}</td>
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
                            </td> <!-- Affichage du manager -->
                            <td>
                                @if($employee->rh->isNotEmpty())
                                    {{ $employee->rh->first()->nom }} {{ $employee->rh->first()->prenom }}
                                @else
                                    Aucun Responsable RH assigné
                                @endif
                            </td>

                        <td>
                            <!-- Liens pour assigner ou changer manager et responsable RH -->
                            <a href="{{ route('user-manager.assign-form', $employee) }}" class="btn btn-info btn-sm">Assigner</a>
                            <a href="{{ route('user-manager.change-form', $employee) }}" class="btn btn-warning btn-sm">Changer</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/plugins/toastr/toastr.min.js') }}"></script>

<script>
$(document).ready(function() {
    @if (session('success'))
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000
    });
    @endif

    @if (session('error'))
    Swal.fire({
        icon: 'error',
        title: '{{ session('error') }}',
        showConfirmButton: false,
        timer: 3000
    });
    @endif
});
</script>
@endsection



