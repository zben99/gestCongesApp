@extends('layouts.template')

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
                            <th>Initialisation congés</th>
                            <td>{{ $user->initial }}</td>
                        </tr>
                    </table>

                    <br>

                    <h4>Historique de Congé</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date de début</th>
                                <th>Date de fin</th>
                                <th>Type de congé</th>
                                <th>Commentaire</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

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
                                <th>Commentaire</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
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
