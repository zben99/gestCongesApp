@extends('layouts.template')

@section('css')
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
  <!-- Custom CSS for status badges -->
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">

                <div class="card">
                    <div class="btn btn-custom-blue btn-block">
                        <h3 class="card-title">Détails de la Demande de Congé</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <p><strong>Employé:</strong> {{ $conge->employe->nom }} {{ $conge->employe->prenom }}</p>
                        @if($conge->employe && $conge->employe->departement)
                            <p><strong>Département:</strong> {{ $conge->employe->departement->name_departement }}</p>
                        @else
                            <p><strong>Département:</strong> Non attribué</p>
                        @endif

                        <p><strong>Email:</strong> {{ $conge->employe->email }}</p>
                        <p><strong>Contact:</strong> {{ $conge->employe->telephone1 }}</p>
                        <p><strong>Type de Congé:</strong> {{ $conge->typeConge->nom }}</p>
                        <p><strong>Date de Début:</strong> {{ $conge->dateDebut }}</p>
                        <p><strong>Date de Fin:</strong> {{ $conge->dateFin }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($conge->status) }}</p>
                        <p><strong>Commentaire:</strong> {{ $conge->commentaire }}</p>

                        <div class="form-group">
                            <label>Lettre de jouissance :</label>
                            @if ($conge->pdf_path)
                              <a href="{{ asset('storage/' . $conge->pdf_path) }}" target="_blank" class="btn btn-custom-blue btn-icon">
                                <i class="fas fa-file"></i> Voir le fichier
                              </a>
                            @else
                              <p>Aucun fichier</p>
                            @endif
                          </div>


                        @if(auth()->user()->profil === 'manager' && $conge->status === 'en attente')
                            <a href="{{ route('conges.approveByManager', $conge) }}" class="btn btn-icon">
                                <i class="fas fa-check status-approved"></i> <span>Approuver</span>
                            </a>
                        @elseif(auth()->user()->profil === 'responsables RH' && $conge->status === 'en attente RH')
                            <a href="{{ route('conges.approveByRh', $conge->id) }}" class="btn btn-icon">
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

                        <a href="{{ route('conges.index') }}" class="btn btn-danger">Retour</a>
                    </div>
                </div>

            </div>
            <!-- /.col -->
            <div class="col-1"></div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
@endsection
