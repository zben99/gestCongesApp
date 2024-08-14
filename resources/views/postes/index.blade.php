@extends('layouts.template')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Liste des Postes') }}</h3>
                    </div>
                    <div class="card-header">
                        <a href="{{ route('postes.create') }}" class="btn btn-primary">Ajouter un Poste</a>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($postes as $poste)
                                <tr>
                                    <td>{{ $poste->name_poste }}</td>
                                    <td>{{ $poste->description }}</td>
                                    <td>
                                        <a href="{{ route('postes.edit', $poste->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form id="delete-form-{{$poste->id}}" method="POST" action="{{ route('postes.destroy', $poste->id) }}" style="display: inline;">
                                            @csrf
                                            @method("DELETE")
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $poste->id }}">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>

                                        <!-- Modal -->
                                        <div class="modal fade" id="deleteModal-{{ $poste->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content bg-danger">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer ce poste ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
                                                        <button type="button" class="btn btn-outline-light" onclick="document.getElementById('delete-form-{{ $poste->id }}').submit();">Confirmer</button>
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
                    <div class="card-footer clearfix">
                        {{ $postes->links('vendor.pagination.custom') }}
                    </div>
                </div> <!-- /.card -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
</section>
@endsection
