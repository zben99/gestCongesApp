@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
  <!-- CSS personnalisé pour les badges de statut -->
@endsection

@section('content')
  <!-- Contenu principal -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="btn btn-custom-blue btn-block">
              <h3 class="card-title">{{ __('Liste des absences') }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <!-- Formulaire de recherche -->
              <form method="GET" action="{{ route('absences.index') }}" class="mb-3">
                @csrf
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <input type="text" name="search" class="form-control" placeholder="Rechercher par matricule ou nom" value="{{ request('search') }}">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <button type="submit" class="btn btn-custom-blue btn-block">Rechercher</button>
                  </div>
                </div>
              </form>

              <div class="card-header mb-3">
                <a href="{{ route('absences.create') }}">
                  <button type="button" class="btn btn-custom-blue btn-block">Ajouter une absence</button>
                </a>
              </div>
              <div style="overflow-x: auto; overflow-y: auto; max-height: 400px;">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Matricule</th>
                    <th>Nom complet</th>
                    <th>Type d'absence</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($absences as $absence)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>{{ $absence->user ? $absence->user->matricule : '' }}</td>
                      <td>{{ $absence->user ? $absence->user->prenom . ' ' . $absence->user->nom : '' }}</td>
                      <td>{{ $absence->typeAbsence ? $absence->typeAbsence->nom : '' }}</td>
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
                        <!-- Bouton "Afficher" -->
                        <a href="{{ route('absences.show', $absence->id) }}" class="btn btn-info btn-sm" title="Afficher les détails">
                          <i class="fas fa-eye"></i>
                        </a>

                        @if(auth()->user()->profil !== 'manager' || $absence->status !== 'en attente')
                          <a href="{{ route('absences.edit', $absence->id) }}" title="Modifier l'absence" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                          </a>

                          <form id="delete-form-{{ $absence->id }}" method="POST" action="{{ route('absences.destroy', $absence->id) }}" style="display: inline;">
                            @csrf
                            @method("DELETE")
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal-{{ $absence->id }}" title="Supprimer l'absence">
                              <i class="fas fa-trash"></i>
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
                          <button type="button" class="btn btn-success btn-sm" title="Valider la demande" data-toggle="modal" data-target="#validateModal-{{ $absence->id }}">
                            <i class="fas fa-check"></i>
                          </button>
                        </form>

                        <form id="reject-form-{{ $absence->id }}" method="POST" action="{{ route('absences.rejectRequest', $absence->id) }}" style="display: inline;">
                          @csrf
                          @method('PUT')
                          <button type="button" class="btn btn-danger btn-sm" title="Rejeter la demande" data-toggle="modal" data-target="#rejectModal-{{ $absence->id }}">
                            <i class="fas fa-times"></i>
                          </button>
                        </form>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              </div>
              <!-- Pagination -->
              <div class="d-flex justify-content-center mt-3">
                {{ $absences->links('vendor.pagination.custom') }}
              </div>
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
