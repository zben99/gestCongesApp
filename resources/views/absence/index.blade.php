@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <!-- Custom CSS for status badges -->
  <style>
    .badge-status {
      font-size: 0.75rem;
      padding: 0.5em 0.75em;
      border-radius: 0.25rem;
      color: #fff;
    }
    .status-pending {
      background-color: #ffc107; /* Amber */
    }
    .status-approved {
      background-color: #28a745; /* Success */
    }
    .status-rejected {
      background-color: #dc3545; /* Danger */
    }
  </style>
@endsection

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{ __('Liste des absences') }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="card-header mb-3">
                <a href="{{ route('absences.create') }}">
                  <button type="button" class="btn btn-lg btn-primary">Ajouter une absence</button>
                </a>
              </div>
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>N°</th>
                  <th>Nom complet</th>
                  <th>Motif</th>
                  <th>Date de début</th>
                  <th>Date de fin</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($absences as $absence)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td>{{ $absence->user->name }}</td>
                  <td>{{ $absence->motif }}</td>
                  <td>{{ $absence->dateDebut->format('d/m/Y') }}</td>
                  <td>{{ $absence->dateFin->format('d/m/Y') }}</td>
                  <td>
                    <span class="badge badge-status 
                      @if ($absence->status === 'en attente') status-pending 
                      @elseif ($absence->status === 'approuvé') status-approved 
                      @elseif ($absence->status === 'rejeté') status-rejected 
                      @endif">
                      {{ ucfirst($absence->status) }}
                    </span>
                  </td>
                  <td>
                    <a href="{{ route('absences.edit', $absence->id) }}" title="Modifier l'absence" class="btn btn-warning">
                      <i class="fas fa-edit"></i> Modifier
                    </a>
                    <form id="delete-form-{{ $absence->id }}" method="POST" action="{{ route('absences.destroy', $absence->id) }}" style="display: inline;">
                      @csrf
                      @method("DELETE")
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $absence->id }}">
                        <i class="fas fa-trash"></i> Supprimer
                      </button>
                    </form>

                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal-{{ $absence->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content bg-danger">
                          <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            Êtes-vous sûr de vouloir supprimer cette absence ?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-outline-light" onclick="document.getElementById('delete-form-{{ $absence->id }}').submit();">Confirmer</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
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
