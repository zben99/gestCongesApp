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
          <h3 class="card-title">Tous les Congés</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <!-- Formulaire de filtrage -->
          <form action="{{ route('rapports.tousConges') }}" method="GET">
            <div class="row">
              <div class="col-md-4">
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

              <div class="col-md-4">
                <label for="year"><strong>Filtrer par année</strong> :</label>
                <select name="year" id="year" class="form-control">
                  <option value="">Toutes les années</option>
                  @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                      {{ $year }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label for="status"><strong>Filtrer par statut</strong> :</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Tous les statuts</option>
                    <option value="approuvé" {{ request('status') == 'approuvé' ? 'selected' : '' }}>Effectuer</option>
                    <option value="refusé" {{ request('status') == 'refusé' ? 'selected' : '' }}>Rejeté</option>
                    <!-- Ajoutez d'autres statuts ici si nécessaire -->
                </select>
            </div>          

              <div class="col-md-4 mt-4">
                <button type="submit" class="btn btn-custom-blue btn-block">Filtrer</button>
              </div>
            </div>
          </form>

          <hr>

          <!-- Résumé et exportation -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Nombre total de congés : {{ $nombreCongesTotal }}</h4>
            <a href="{{ route('rapports.export', ['department_id' => $departmentId, 'year' => request('year'), 'status' => request('status')]) }}" class="btn btn-custom-blue btn-block">
                <i class="fas fa-file-excel"></i> Exporter
            </a>
          </div>

          <!-- Tableau des congés -->
          <table class="table table-bordered table-over">
            <thead class="thead-dark">
              <tr>
                <th>Matricule</th>
                <th>Employé</th>
                <th>Département</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($congesTous as $conge)
                <tr>
                  <td>{{ $conge->employe->matricule }}</td>
                  <td>{{ $conge->employe->nom }} {{ $conge->employe->prenom }}</td>
                  <td>{{ $conge->employe->departement->name_departement ?? 'Non attribué' }}</td>
                  <td>{{ \Carbon\Carbon::parse($conge->dateDebut)->format('d/m/Y') }}</td>
                  <td>{{ \Carbon\Carbon::parse($conge->dateFin)->format('d/m/Y') }}</td>
                  <td>{{ $conge->status }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
           <!-- Afficher les liens de pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $congesTous->links('vendor.pagination.custom') }}
        </div>
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

