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
              <h3 class="card-title">{{ __('Liste des administrateurs') }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="card-header">
                <a href="{{ route('admins.create') }}">
                  <button type="button" class="btn btn-lg btn-primary">Ajouter un administrateur</button>
                </a>
              </div>
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>N°</th>
                  <th>Nom complet</th>
                  <th>Email</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>
                    <a href="{{ route('admins.edit', $user) }}" title="Modifier l'article" class="btn btn-primary">
                      <i class="fas fa-edit"></i> Modifier
                    </a>
                    <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('admins.destroy', $user) }}" style="display: inline;">
                      @csrf
                      @method("DELETE")
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $user->id }}">
                        <i class="fas fa-trash"></i> Supprimer
                      </button>
                    </form>

                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content bg-danger">
                          <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            Êtes-vous sûr de vouloir supprimer cet utilisateur ?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-outline-light" onclick="document.getElementById('delete-form-{{ $user->id }}').submit();">Confirmer</button>
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
