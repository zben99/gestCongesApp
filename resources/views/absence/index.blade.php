@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <!-- Custom CSS for status badges -->
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
              <!-- Formulaire de recherche -->
              <form method="GET" action="{{ route('absences.index') }}" class="mb-3">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <input type="text" name="search" class="form-control" placeholder="Rechercher par matricule ou nom" value="{{ request('search') }}">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">Rechercher</button>
                  </div>
                </div>
              </form>
              
              <div class="card-header mb-3">
                <a href="{{ route('absences.create') }}">
                  <button type="button" class="btn btn-lg btn-primary">Ajouter une absence</button>
                </a>
              </div>
              
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Matricule</th>
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
                      <td>{{ $absence->user ? $absence->user->matricule : '' }}</td>
                      <td>{{ $absence->user ? $absence->user->prenom . ' ' . $absence->user->nom : '' }}</td>
                      <td>{{ $absence->motif }}</td>
                      <td>{{ $absence->dateDebut }}</td>
                      <td>{{ $absence->dateFin }}</td>
                      <td>
                        <span class="
                          @if ($absence->status === 'en attente') status-pending 
                          @elseif ($absence->status === 'approuvé') status-approved 
                          @elseif ($absence->status === 'refusé') status-rejected 
                          @endif
                        ">
                          {{ ucfirst($absence->status) }}
                        </span>
                      </td>
                      <td>
                        @if(auth()->user()->profil !== 'manager' || $absence->status !== 'en attente')
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
                        @endif

                        <!-- Modal de Confirmation pour la Suppression -->
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

                        <!-- Modal de Confirmation pour la Validation -->
                        <div class="modal fade" id="validateModal-{{ $absence->id }}" tabindex="-1" role="dialog" aria-labelledby="validateModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content bg-success">
                              <div class="modal-header">
                                <h5 class="modal-title" id="validateModalLabel">Confirmation de validation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                Êtes-vous sûr de vouloir valider cette demande d'absence ?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
                                <button type="button" class="btn btn-outline-light" onclick="document.getElementById('validate-form-{{ $absence->id }}').submit();">Confirmer</button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Modal de Confirmation pour le Rejet -->
                        <div class="modal fade" id="rejectModal-{{ $absence->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content bg-danger">
                              <div class="modal-header">
                                <h5 class="modal-title" id="rejectModalLabel">Confirmation de rejet</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                Êtes-vous sûr de vouloir rejeter cette demande d'absence ?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
                                <button type="button" class="btn btn-outline-light" onclick="document.getElementById('reject-form-{{ $absence->id }}').submit();">Confirmer</button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Boutons de validation et de rejet pour les Managers -->
                        @if(auth()->user()->profil === 'manager' && $absence->status === 'en attente')
                        <form id="validate-form-{{ $absence->id }}" method="POST" action="{{ route('absences.validateRequest', $absence->id) }}" style="display: inline;">
                          @csrf
                          @method('PUT')
                          <button type="button" class="btn btn-success" title="Valider la demande" data-toggle="modal" data-target="#validateModal-{{ $absence->id }}">
                            <i class="fas fa-check"></i> Valider
                          </button>
                        </form>
                        <form id="reject-form-{{ $absence->id }}" method="POST" action="{{ route('absences.rejectRequest', $absence->id) }}" style="display: inline;">
                          @csrf
                          @method('PUT')
                          <button type="button" class="btn btn-danger" title="Rejeter la demande" data-toggle="modal" data-target="#rejectModal-{{ $absence->id }}">
                            <i class="fas fa-times"></i> Rejeter
                          </button>
                        </form>
                        @endif
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
    });

    @if (session('success'))
      toastr.success("{{ session('success') }}");
    @elseif (session('error'))
      toastr.error("{{ session('error') }}");
    @endif
  </script>
@endsection
