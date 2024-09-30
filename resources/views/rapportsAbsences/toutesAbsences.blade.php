@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
  <!-- CSS personnalisé pour le rapport des absences -->
@endsection

@section('content')
  <!-- Contenu principal -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="btn btn-custom-blue btn-block">
          <h3 class="card-title">Toutes les Absences</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <!-- Afficher les messages d'erreur -->
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <!-- Formulaire de filtrage -->
          <form action="{{ route('rapportsAbsences.toutesAbsences') }}" method="GET">
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
                    @foreach($absenceTypes as $type)
                        <option value="{{ $type }}" {{ request('status') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @endforeach
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
            <h4>Nombre total d'absences : {{ $nombreAbsencesTotal }}</h4>
            <a href="{{ route('rapportsAbsences.exportAbsences', ['departmentId' => $departmentId, 'year' => request('year'), 'status' => request('status')]) }}" class="btn btn-custom-blue btn-block">
                <i class="fas fa-file-excel"></i> Exporter les Absences
            </a>
          </div>

          <!-- Tableau des absences -->
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
              @foreach($absences as $absence)
                <tr>
                  <td>{{ $absence->user->matricule }}</td>
                  <td>{{ $absence->user->nom }} {{ $absence->user->prenom }}</td>
                  <td>{{ $absence->user->departement->name_departement ?? 'Non attribué' }}</td>
                  <td>{{ \Carbon\Carbon::parse($absence->dateDebut)->format('d/m/Y') }}</td>
                  <td>{{ \Carbon\Carbon::parse($absence->dateFin)->format('d/m/Y') }}</td>
                  <td>{{ $absence->status }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          
          <!-- Afficher les liens de pagination -->
          <div class="d-flex justify-content-center mt-4">
              {{ $absences->links('vendor.pagination.custom') }}
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
