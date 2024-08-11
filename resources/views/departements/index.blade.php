@extends('layouts.template')



@section('content')

    <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Liste des Départements') }}</h3>
                    </div>
                    <div class="card-header">
                        <a href="{{ route('departements.create') }}" class="btn btn-lg btn-primary">Ajouter un Département</a>
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
                        @foreach ($departements as $departement)
                            <tr>
                                <td>{{ $departement->name_departement }}</td>
                                <td>{{ $departement->description }}</td>
                                <td>
                                    <a href="{{ route('departements.edit', $departement->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form id="delete-form-{{$departement->id}}" method="POST" action="{{ route('departements.destroy', $departement->id) }}" style="display: inline;">
                                        @csrf
                                        @method("DELETE")

                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $departement->id }}">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>


                                    <!-- Modal -->
                                    <div class="modal fade" id="deleteModal-{{ $departement->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content bg-danger">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer ce département ?
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Annuler</button>
                                            <button type="button" class="btn btn-outline-light" onclick="document.getElementById('delete-form-{{ $departement->id }}').submit();">Confirmer</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>                 <!-- /.card-body -->
            </div>         <!-- /.card -->
        </div>        <!-- /.col -->
    </div>     <!-- /.row -->
</div>
 </section>
    <!-- /.container-fluid -->
@endsection

