<!-- resources/views/conges/index.blade.php -->
@extends('layouts.template')



@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{ __('Liste des Congés') }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">


              <div class="card-header mb-3">
                <a href="{{ route('conges.create') }}" class="btn btn-primary">Ajouter une Demande de Congé</a>
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
                    <tr class="{{ $conge->status == 'approuvé' ? 'bg-success' : ($conge->status == 'refusé' ? 'bg-danger' : '') }}">
                        <td>{{ $conge->employe->nom }} {{ $conge->employe->prenom }}</td>
                        <td>{{ $conge->typeConges }}</td>
                        <td>{{ $conge->dateDebut }}</td>
                        <td>{{ $conge->dateFin }} </td>
                        <td>{{ ucfirst($conge->status) }}</td>
                        <td>
                            @if(auth()->user()->profil === 'manager' && $conge->status === 'en attente')
                            <a href="{{ route('conges.approveByManager', $conge) }}" class="btn btn-success btn-sm">Approuver</a>
                        @elseif(auth()->user()->profil === 'responsables RH' && $conge->status === 'en attente RH')
                            <a href="{{ route('conges.approveByRh', $conge->id) }}" class="btn btn-success btn-sm">Approuver</a>
                        @endif

                        @if((auth()->user()->profil === 'manager' && $conge->status === 'en attente') || auth()->user()->profil === 'responsables RH')
                            <a href="{{ route('conges.reject', $conge->id) }}" class="btn btn-danger btn-sm">Refuser</a>
                        @endif


                            <a href="{{ route('conges.show', $conge->id) }}" class="btn btn-info btn-sm">Voir</a>

                            <a href="{{ route('conges.edit', $conge->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form id="delete-form-{{$conge->id}}" method="POST" action="{{ route('conges.destroy', $conge->id) }}" style="display: inline;">
                                @csrf
                                @method("DELETE")

                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $conge->id }}">
                                    <i class="fas fa-trash"></i> Supprimer
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
