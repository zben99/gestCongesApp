@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
@endsection

@section('content')
<br>
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Détails de l'utilisateur</h3>
                </div>
                <!-- /.card-header -->

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Matricule</th>
                            <td>{{ $user->matricule }}</td>
                        </tr>
                        <tr>
                            <th>Nom</th>
                            <td>{{ $user->nom }}</td>
                        </tr>
                        <tr>
                            <th>Prénom(s)</th>
                            <td>{{ $user->prenom }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Téléphone 1</th>
                            <td>{{ $user->telephone1 }}</td>
                        </tr>
                        <tr>
                            <th>Téléphone 2</th>
                            <td>{{ $user->telephone2 }}</td>
                        </tr>
                        <tr>
                            <th>Date de Naissance</th>
                            <td>{{ \Illuminate\Support\Carbon::parse($user->birth_date)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Profil</th>
                            <td>{{ $user->profil }}</td>
                        </tr>
                        <tr>
                            <th>Département</th>
                            <td>{{ $user->departement ? $user->departement->name_departement : '' }}</td>
                        </tr>

                        <tr>
                            <th>Poste</th>
                            <td>{{ $user->poste ? $user->poste->name_poste : '' }}</td>
                        </tr>
                        <tr>
                            <th>Date d'Arrivée</th>
                            <td>{{ \Illuminate\Support\Carbon::parse($user->arrival_date)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Date d'initialisation</th>
                            <td>{{ \Illuminate\Support\Carbon::parse($user->initialization_date)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Congés restant</th>
                            <td>{{ $congeRestant }}</td>
                        </tr>

                        <tr>
                            <th>Jours de congé bonus</th>
                            <td>{{ $user->joursBonus }}</td>
                        </tr>
                    </table>

                    <!-- Boutons pour ajouter des jours de congé en bonus -->
                    <div class="mt-3">
                        <h4>Ajouter des jours de congé bonus</h4>
                        <form action="{{ route('ajouter.jours.bonus', $user->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="joursBonus">Nombre de jours à ajouter :</label>
                                <input type="number" id="joursBonus" name="joursBonus" class="form-control" required>
                            </div> <br>
                            <button type="submit" class="btn btn-success">Ajouter</button>
                        </form>
                    </div>

                    <br>

                    <h4>Historique de Congé</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date de début</th>
                                <th>Date de fin</th>
                                <th>Type de congé</th>
                                <th>Commentaire</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->conges as $conge)
                            <tr class="{{ $conge->status == 'approuvé' ? 'bg-success' : ($conge->status == 'refusé' ? 'bg-danger' : '') }}">
                                <td>{{ $conge->dateDebut }}</td>
                                <td>{{ $conge->dateFin }} </td>
                                <td>{{ $conge->typeConges }}</td>
                                <td>{{ $conge->commentaire }}</td>
                                <td>{{ ucfirst($conge->status) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <br>

                    <h4>Historique d'Absence</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date de début</th>
                                <th>Date de fin</th>
                                <th>Raison</th>
                                <th>statut</th>
                                <th>Commentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->absences as $absence)
                            <tr>
                                <td>{{ $absence->dateDebut }}</td>
                                <td>{{ $absence->dateFin }}</td>
                                <td>{{ $absence->motif }}</td>
                                <td>
                                    <span class="
                                        @if ($absence->status === 'en attente') status-pending
                                        @elseif ($absence->status === 'approuvé') status-approved
                                        @elseif ($absence->status === 'refusé') status-rejected
                                        @endif
                                    ">
                                        {{ ucfirst($absence->status) }}
                                    </span>
                                </td>
                                <td>{{ $absence->commentaire }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <a href="{{ route('admins.index') }}" class="btn btn-danger">Retour</a>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
</section>
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

    });
    @endif

    @if (session('error'))
    Swal.fire({
        icon: 'error',
        title: '{{ session('error') }}',
        showConfirmButton: false,

    });
    @endif
});
</script>
@endsection



