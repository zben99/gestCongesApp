@extends('layouts.template')

@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Absences en Attente de Validation</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-striped">
            <thead class="thead-dark">
              <tr>
                <th>Matricule</th>
                <th>Employé</th>
                <th>Département</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Motif</th>
              </tr>
            </thead>
            <tbody>
              @foreach($absencesEnAttente as $absence)
                <tr>
                  <td>{{ $absence->user->matricule }} </td>
                  <td>{{ $absence->user->nom }} {{ $absence->user->prenom }}</td>
                  <td>{{ $absence->user->departement->name_departement ?? 'Non attribué' }}</td>
                  <td>{{ \Carbon\Carbon::parse($absence->dateDebut)->format('d/m/Y') }}</td>
                  <td>{{ \Carbon\Carbon::parse($absence->dateFin)->format('d/m/Y') }}</td>
                  <td>{{ $absence->motif }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
@endsection
