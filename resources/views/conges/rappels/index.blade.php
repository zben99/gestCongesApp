@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
              <h3 class="card-title">{{ __('Liste des Rappels') }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <!-- Informations sur le congé -->
              <div class="mb-4">
                <h5>Informations sur le Congé :</h5>
                <ul>
                  <li>Employé : <strong>{{ $conge->employe->nom }} {{ $conge->employe->prenom }}</strong></li>
                  <li>Type de Congé : <strong>{{ $conge->typeConge->nom }}</strong></li>
                  <li>Date de Début : <strong>{{ $conge->dateDebut }}</strong></li>
                  <li>Date de Fin : <strong>{{ $conge->dateFin }}</strong></li>
                  <li>Status : <strong>{{ ucfirst($conge->status) }}</strong></li>
                </ul>
              </div>

              <!-- Recherche -->
              <div class="mb-2">
                <form action="{{ route('rappels.index', ['conge' => $conge->id]) }}" method="GET" class="form-inline">
                  <div class="form-group mb-2">
                    <input type="text" name="dateDebutRappel" class="form-control mr-2" placeholder="Date de début" value="{{ request('dateDebutRappel') }}">
                  </div>
                  <button type="submit" class="btn btn-custom-blue">Rechercher</button>
                </form>
              </div>

              <div class="card-header mb-2">
                <a href="{{ route('rappels.create', ['conge' => $conge->id]) }}" class="btn btn-custom-blue btn-icon">
                    <i class="fas fa-plus"></i> <span>Ajouter Rappel</span>
                </a>
              </div>

              <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Date de Début</th>
                    <th>Date de Fin</th>
                    <th>Actions</th>
                  </tr>
                </thead>

                <tbody>
                    @forelse ($rappels as $rappel)
                    <tr>
                        <td>{{ $rappel->dateDebutRappel }}</td>
                        <td>{{ $rappel->dateFinRappel ?? 'Non défini' }}</td>
                        <td>

                          <form id="delete-form-{{ $rappel->id }}" method="POST" action="{{ route('rappels.destroy', $rappel->id) }}" style="display: inline;">
                              @csrf
                              @method("DELETE")

                              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal-{{ $rappel->id }}">
                                  <i class="fas fa-trash"></i>
                              </button>
                          </form>

                        <!-- Modal -->
                        <div class="modal fade" id="deleteModal-{{ $rappel->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content bg-danger">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer ce rappel ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
                                        <button type="button" class="btn btn-outline-light" onclick="document.getElementById('delete-form-{{ $rappel->id }}').submit();">Confirmer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Aucun rappel trouvé</td>
                    </tr>
                    @endforelse
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
