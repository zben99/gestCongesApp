@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
  <!-- CSS personnalisé pour le rapport -->
@endsection


@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="btn btn-custom-blue btn-block">
          <h3 class="card-title">Employés Actuellement en Absence</h3>
        </div>
        <div class="card-body">
          <form action="{{ route('rapportsAbsences.enCours') }}" method="GET">
            <div class="row">
              <div class="col-md-6">
                <label for="department_id"><strong>Filtrer par département</strong> :</label>
                <select name="department_id" id="department_id" class="form-control">
                  <option value="">Tous les départements</option>
                  @foreach($departements as $departement)
                    <option value="{{ $departement->id }}" {{ $departmentId == $departement->id ? 'selected' : '' }}>
                      {{ $departement->name_departement }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3 mt-4">
                <button type="submit" class="btn btn-custom-blue btn-block">Filtrer</button>
              </div>
            </div>
          </form>

          <hr>

          <h4>Nombre d'employés en absence : {{ $nombreAbsencesEnCours }}</h4>

          <table class="table table-bordered table-over">
            <thead class="thead-dark">
              <tr>
                <th>Matricule</th>
                <th>Employé</th>
                <th>Département</th>
                <th>Date début</th>
                <th>Date fin</th>
              </tr>
            </thead>
            <tbody>
              @foreach($absencesEnCours as $absence)
                <tr>
                  <td>{{ $absence->user->matricule }} </td>
                  <td>{{ $absence->user->nom }} {{ $absence->user->prenom }}</td>
                  <td>{{ $absence->user->departement->name_departement ?? 'Non attribué' }}</td>
                  <td>{{ \Carbon\Carbon::parse($absence->dateDebut)->format('d/m/Y') }}</td>
                  <td>{{ \Carbon\Carbon::parse($absence->dateFin)->format('d/m/Y') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
@endsection
