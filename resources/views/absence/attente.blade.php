@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="btn btn-custom-blue btn-block">
              <h3 class="card-title">Absences en attente de validation depuis plus de 72 heures</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              @if($absences->isEmpty())
                  <p>Aucune demande d'absence en attente depuis plus de 72 heures.</p>
              @else
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Nom de l'employé</th>
                      <th>Motif</th>
                      <th>Date de début</th>
                      <th>Date de fin</th>
                      <th>Date de création</th>
                      <th>Manager responsable</th>
                      <th>Numéro du manager</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($absences as $absence)
                      <tr>
                        <td>{{ $absence->user->prenom }} {{ $absence->user->nom }}</td>
                        <td>{{ $absence->motif }}</td>
                        <td>{{ $absence->dateDebut->format('d/m/Y') }}</td>
                        <td>{{ $absence->dateFin->format('d/m/Y') }}</td>
                        <td>{{ $absence->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                          @if($absence->approved_by)
                            @php
                              $manager = \App\Models\User::find($absence->approved_by);
                            @endphp
                            {{ $manager->prenom }} {{ $manager->nom }}
                          @else
                            Aucun manager assigné
                          @endif
                        </td>
                        <td>
                          @if($absence->approved_by)
                            {{ $manager->telephone1 }}
                          @else
                            N/A
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-3">
                  {{ $absences->links('vendor.pagination.custom') }}
                </div>
              @endif
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
@endsection

@section('js')
  <!-- SweetAlert2 -->
  <script src="{{ asset('/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <!-- Toastr -->
  <script src="{{ asset('/plugins/toastr/toastr.min.js') }}"></script>
  <!-- DataTables -->
  <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <!-- Custom JS -->
  <script>
    $(function () {
      $("#example2").DataTable();

      @if (session('success'))
        toastr.success("{{ session('success') }}");
      @elseif (session('error'))
        toastr.error("{{ session('error') }}");
      @endif
    });
  </script>
@endsection
