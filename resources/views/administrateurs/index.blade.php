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
              <h3 class="card-title">{{ __('Liste des utilisateurs') }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <br>
                <form method="POST" action="{{ route('admins.import') }}" enctype="multipart/form-data" >
                          <!-- CSRF Token -->
                          @csrf
                          <div class="row">
                              <div class="col">
                                  <input type="file" class="form-control" id="file" name="file" required>
                              </div>
                              <div class="col">
                                  <button type="submit" class="btn btn-custom-blue ">Importer</button>
                              </div>
                          </div>


                  </form>
                  <br>
              <div class="card-header">
                <a href="{{ route('admins.create') }}">
                  <button type="button" class="btn btn-custom-blue btn-block">Ajouter un utilisateur</button>
                </a>
              </div>


              <!-- Formulaire de recherche et sélection du nombre de résultats par page -->
              <div class="d-flex justify-content-between align-items-center mt-3">
                <form method="GET" action="{{ route('admins.index') }}" class="form-inline">
                  <div class="input-group mb-3">
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Rechercher un employé" aria-label="Rechercher un employé">
                    <select name="per_page" onchange="this.form.submit()" class="form-control ml-2">
                      <option value="4" {{ $perPage == 4 ? 'selected' : '' }}>4</option>
                      <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                      <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                      <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-custom-blue btn-block">Rechercher</button>
                    </div>
                  </div>
                </form>
              </div>

              <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>matricule</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>profil</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>{{ $user->matricule }}</td>
                      <td>{{ $user->nom }} {{ $user->prenom }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->profil }}</td>
                      <td>
                        <a href="{{ route('admins.show', $user) }}" title="Afficher les détails" class="btn btn-custom-blue btn-block">
                          <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admins.edit', $user) }}" title="Modification" class="btn btn-primary">
                          <i class="fas fa-edit"></i>
                        </a>
                        <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('admins.destroy', $user) }}" style="display: inline;">
                          @csrf
                          @method("DELETE")
                          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $user->id }}">
                            <i class="fas fa-trash"></i>
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

              <!-- Pagination -->
              <div class="d-flex justify-content-center mt-3">
                {{ $users->appends(['per_page' => $perPage, 'search' => $search])->links('vendor.pagination.custom') }}
              </div>

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
<script src="{{ asset('/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/plugins/toastr/toastr.min.js') }}"></script>

<script>
$(document).ready(function() {
    @if (session('success'))
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000
    });
    @endif

    @if (session('error'))
    Swal.fire({
        icon: 'error',
        title: '{{ session('error') }}',
        showConfirmButton: false,
        timer: 3000
    });
    @endif
});
</script>
@endsection



