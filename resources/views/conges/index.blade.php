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
              <!-- Recherche -->
              <div class="mb-2">
                <form action="{{ route('conges.index') }}" method="GET" class="form-inline">
                  <div class="form-group mb-2">
                    <select name="status" class="form-control mr-2">
                      <option value="">{{ __('Statut') }}</option>
                      <option value="en attente" {{ request('status') === 'en attente' ? 'selected' : '' }}>En attente Manager</option>
                      <option value="en attente RH" {{ request('status') === 'en attente RH' ? 'selected' : '' }}>En attente RH</option>
                      <option value="approuvé" {{ request('status') === 'approuvé' ? 'selected' : '' }}>Approuvé</option>
                      <option value="refusé" {{ request('status') === 'refusé' ? 'selected' : '' }}>Refusé</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-custom-blue">Rechercher</button>
                </form>
              </div>

              <div class="card-header mb-2">
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
                    @forelse ($conges as $conge)
                    <tr>
                        <td>{{ $conge->employe->nom }} {{ $conge->employe->prenom }}</td>
                        <td>{{ $conge->typeConge->nom }}</td>
                        <td>{{ $conge->dateDebut }}</td>
                        <td>{{ $conge->dateFin }} </td>
                        <td>
                        <span class="
                          @if ($conge->status === 'en attente') status-pending
                          @elseif ($conge->status === 'en attente RH') status-pending
                          @elseif ($conge->status === 'approuvé') status-approved
                          @elseif ($conge->status === 'refusé') status-rejected
                          @endif
                        ">
                          {{ ucfirst($conge->status) }}
                        </span>
                      </td>
                      <td>
                          @if(auth()->user()->profil === 'manager' && $conge->status === 'en attente')
                              <a href="{{ route('conges.approveByManager', $conge) }}" class="btn btn-icon">
                                  <i class="fas fa-check status-approved"></i> <span>Approuver</span>
                              </a>
                          @elseif(auth()->user()->profil === 'responsables RH' && $conge->status === 'en attente RH')
                              <a href="{{ route('conges.approveByRh', $conge) }}" class="btn btn-icon">
                                  <i class="fas fa-check status-approved"></i> <span>Approuver</span>
                              </a>
                          @endif

                          @if(
                              (auth()->user()->profil === 'manager' && $conge->status === 'en attente') || 
                              (auth()->user()->profil === 'responsables RH' && $conge->status === 'en attente RH')
                          )
                              <a href="{{ route('conges.reject', $conge->id) }}" class="btn btn-icon">
                                  <i class="fas fa-times status-rejected"></i> <span>Refuser</span>
                              </a>
                          @endif

                          <a href="{{ route('conges.show', $conge->id) }}" class="btn btn-icon">
                              <i class="fas fa-eye icon-view"></i>
                          </a>

                          @if(
                              (auth()->user()->id === $conge->UserId && $conge->status === 'en attente') || 
                              auth()->user()->profil === 'administrateurs'
                          )
                              <a href="{{ route('conges.edit', $conge->id) }}" class="btn btn-warning btn-sm">
                                  <i class="fas fa-edit"></i>
                              </a>
                              <form id="delete-form-{{ $conge->id }}" method="POST" action="{{ route('conges.destroy', $conge->id) }}" style="display: inline;">
                                  @csrf
                                  @method("DELETE")

                                  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal-{{ $conge->id }}">
                                      <i class="fas fa-trash"></i>
                                  </button>
                              </form>
                          @endif
                      </td>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun congé trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
              </table>

              <!-- Pagination -->
              <div class="d-flex justify-content-center mt-3">
                {{ $conges->appends(request()->input())->links('vendor.pagination.custom') }}
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

