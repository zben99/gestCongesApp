@extends('layouts.template')

@section('css')
             <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
@endsection
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="btn btn-custom-blue btn-block">
                        <h3 class="card-title">{{ __('Liste des Types d\'absences') }}</h3>
                    </div>
                    <div class="card-header">
                        <a href="{{ route('typeAbsences.create') }}" class="btn btn-custom-blue btn-block">Création</a>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Durée max</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($typeAbsences as $typeAbsence)
                                <tr>
                                    <td>{{ $typeAbsence->nom }}</td>
                                    <td>{{ $typeAbsence->description }}</td>
                                    <td>{{ $typeAbsence->duree_max }} jours</td>
                                    <td>
                                        <!-- Bouton View -->
                                        <a href="{{ route('typeAbsences.show', $typeAbsence->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('typeAbsences.edit', $typeAbsence->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="delete-form-{{$typeAbsence->id}}" method="POST" action="{{ route('typeAbsences.destroy', $typeAbsence->id) }}" style="display: inline;">
                                            @csrf
                                            @method("DELETE")
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $typeAbsence->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                        <!-- Modal -->
                                        <div class="modal fade" id="deleteModal-{{ $typeAbsence->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content bg-danger">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer ce type d'absence ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
                                                        <button type="button" class="btn btn-outline-light" onclick="document.getElementById('delete-form-{{ $typeAbsence->id }}').submit();">Confirmer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- /.card -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
</section>
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

            });
            @endif

            @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,

            });
            @endif
        });
    </script>
@endsection
