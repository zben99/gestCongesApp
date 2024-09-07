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
  <!-- Contenu principal -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="btn btn-custom-blue btn-block">
          <h3 class="card-title">Personnes Partant en Congé le Mois Prochain</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <!-- Formulaire de filtrage -->
          <form action="{{ route('rapports.moisProchain') }}" method="GET">
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

          <!-- Résumé et exportation -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Nombre de personnes partant en congé : {{ $nombreConges }}</h4>
            <a href="{{ route('rapports.export', ['department_id' => $departmentId]) }}" class="btn btn-success">Exporter en Excel</a>

          </div>

          <!-- Tableau des congés -->
          <table class="table table-bordered table-hover">
            <thead class="thead-dark">
              <tr>
                <th>Nom</th>
                <th>Département</th>
                <th>Type de Congé</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
              </tr>
            </thead>
            <tbody>
              @foreach($congesMoisProchain as $conge)
                <tr>
                  <td>{{ $conge->employe->nom }} {{ $conge->employe->prenom }}</td>
                  <td>{{ $conge->employe->departement->name_departement ?? 'Non attribué' }}</td>
                  <td>{{ $conge->typeConge->nom }}</td>
                  <td>{{ $conge->dateDebut}}</td>
                  <td>{{ $conge->dateFin }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </section>
  <!-- /.content -->
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="{{ asset('/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('/plugins/toastr/toastr.min.js') }}"></script>

@if (session('success'))
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        });
    </script>
@endif

@if (session('error'))
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        });
    </script>
@endif
@endsection
