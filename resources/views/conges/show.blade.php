<!-- resources/views/conges/show.blade.php -->
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
                        <p><strong>Type de Congé:</strong> {{ $conge->typeConges }}</p>
                        <p><strong>Date de Début:</strong> {{ $conge->dateDebut }}</p>
                        <p><strong>Date de Fin:</strong> {{ $conge->dateFin }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($conge->status) }}</p>
                        <p><strong>Commentaire:</strong> {{ $conge->commentaire }}</p>
                        
                        @if($conge->status == 'en attente')
                            @if(auth()->user()->profil == 'manager')
                                <a href="{{ route('conges.approveByManager', $conge->id) }}" class="btn btn-success">Approuver</a>
                                <a href="{{ route('conges.reject', $conge->id) }}" class="btn btn-danger">Refuser</a>
                            @elseif(auth()->user()->profil == 'responsables RH')
                                <a href="{{ route('conges.approveByRh', $conge->id) }}" class="btn btn-success">Approuver</a>
                                <a href="{{ route('conges.reject', $conge->id) }}" class="btn btn-danger">Refuser</a>
                            @endif
                        @endif
                        
                        <a href="{{ route('conges.index') }}" class="btn btn-custom-blue btn-block">Retour</a>
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
