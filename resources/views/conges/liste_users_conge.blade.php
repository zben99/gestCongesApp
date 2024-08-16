@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Personnes autorisées à partir en congés </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              @if($users->isEmpty())
                  <p>Aucun</p>
              @else
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                        <th>N°</th>
                        <th>matricule</th>
                        <th>Nom complet</th>
                        <th>Congés restant</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $user)
                    @php
                    $days = (new \DateTime(now()))->diff(new \DateTime($user->initialization_date))->days + 1;
                        $nbreConge=($days*2.5)/30;
                        $congeRestant= floor($nbreConge+$user->initial - $user->pris);

                    @endphp

                    @if ($congeRestant>29)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $user->matricule }}</td>
                            <td>{{ $user->nom }} {{ $user->prenom }}</td>

                            <td>{{ $congeRestant }}</td>

                        </tr>
                    @endif

                    @endforeach
                  </tbody>
                </table>

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
