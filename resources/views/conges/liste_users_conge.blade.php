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
                        <h3 class="card-title">Personnes autorisées à partir en congés</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if($users->isEmpty())
                            <p>Aucun</p>
                        @else
                            @php
                                // Calculer les congés restants pour chaque utilisateur et trier la collection
                                $users = $users->map(function($user) {
                                    $days1 = (new \DateTime(now()))->diff(new \DateTime($user->arrival_date))->days + 1;
                                    $days = (new \DateTime(now()))->diff(new \DateTime($user->initialization_date))->days + 1;
                                    $nbreConge = ($days * 2.5) / 30;
                                    $user->congeRestant = floor(($nbreConge + $user->initial) - $user->pris);
                                    $user->days1 = $days1;
                                    return $user;
                                })->sortByDesc('congeRestant'); // Trier par congés restants

                            @endphp
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Matricule</th>
                                        <th>Nom complet</th>
                                        <th>Contact</th>
                                        <th>Congés restants</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        @php
                                            // Définir la classe en fonction des jours restants
                                            $rowClass = '';
                                            if ($user->congeRestant > 60) {
                                                $rowClass = 'table-danger'; // Rouge
                                            } elseif ($user->congeRestant > 30) {
                                                $rowClass = 'table-warning'; // Jaune
                                            } else {
                                                $rowClass = 'table-success'; // Vert
                                            }
                                        @endphp

                                        @if ($user->days1 >= 360)
                                            <tr class="{{ $rowClass }}">
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $user->matricule }}</td>
                                                <td>{{ $user->nom }} {{ $user->prenom }}</td>
                                                <td>{{ $user->telephone1 }}</td>
                                                <td>{{ $user->congeRestant }}</td>
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
