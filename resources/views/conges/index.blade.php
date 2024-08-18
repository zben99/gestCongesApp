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
              <h3 class="card-title">{{ __('Liste des Congés') }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="card-header mb-3">
                <a href="{{ route('conges.create') }}" class="btn btn-custom-blue btn-icon">
                    <i class="fas fa-plus"></i> <span>Demande Congé</span>
                </a>
              </div>

              <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Employé</th>
                    <th>Type de Congé</th>
                    <th>Date de Début</th>
                    <th>Date de Fin</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>

                <tbody>
                    @foreach ($conges as $conge)
                    <tr>
                        <td>{{ $conge->employe->nom }} {{ $conge->employe->prenom }}</td>
                        <td>{{ $conge->typeConges }}</td>
                        <td>{{ $conge->dateDebut }}</td>
                        <td>{{ $conge->dateFin }} </td>
                        <td>
                        <span class="
                          @if ($conge->status === 'en attente') status-pending 
                          @elseif ($conge->status === 'approuvé') status-approved 
                          @elseif ($conge->status === 'refusé') status-rejected 
                          @endif
                        ">
                          {{ ucfirst($conge->status) }}
                        </span>
                      </td>
                        <td>
                            @if(auth()->user()->profil === 'manager' && $conge->status === 'en attente')
                            <a href="{{ route('conges.approveByManager', $conge) }}" class="btn btn-custom-blue btn-icon">
                                <i class="fas fa-check"></i> <span>Approuver</span>
                            </a>
                        @elseif(auth()->user()->profil === 'responsables RH' && $conge->status === 'en attente RH')
                            <a href="{{ route('conges.approveByRh', $conge->id) }}" class="btn btn-custom-blue btn-icon">
                                <i class="fas fa-check"></i> <span>Approuver</span>
                            </a>
                        @endif

                        @if((auth()->user()->profil === 'manager' && $conge->status === 'en attente') || auth()->user()->profil === 'responsables RH')
                            <a href="{{ route('conges.reject', $conge->id) }}" class="btn btn-custom-blue btn-icon">
                                <i class="fas fa-times"></i> <span>Refuser</span>
                            </a>
                        @endif

                            <a href="{{ route('conges.show', $conge->id) }}" class="btn btn-custom-blue btn-icon">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('conges.edit', $conge->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> 
                            </a>
                            <form id="delete-form-{{$conge->id}}" method="POST" action="{{ route('conges.destroy', $conge->id) }}" style="display: inline;">
                                @csrf
                                @method("DELETE")

                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal-{{ $conge->id }}">
                                    <i class="fas fa-trash"></i> 
                                </button>
                            </form>

                            <!-- Modal -->
                            <div class="modal fade" id="deleteModal-{{ $conge->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content bg-danger">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer cette demande de congé ?
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
                                    <button type="button" class="btn btn-outline-light" onclick="document.getElementById('delete-form-{{ $conge->id }}').submit();">Confirmer</button>
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
