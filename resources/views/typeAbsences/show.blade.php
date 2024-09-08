
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
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="btn btn-custom-blue btn-block">
                <h3 class="card-title">Détails du Type d'Absence</h3>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>Nom : </strong>{{ $typeAbsence->nom }}</li>
                    <li class="list-group-item"><strong>Description : </strong>{{ $typeAbsence->description}}</li>
                    <li class="list-group-item"><strong>Durée Maximale : </strong>{{ $typeAbsence->duree_max }} jours</li>
                    <li class="list-group-item"><strong>Justificatif Requis : </strong>{{ $typeAbsence->justificatif_requis ? 'Oui' : 'Non' }}</li>
                    <li class="list-group-item"><strong>Déductible des Congés : </strong>{{ $typeAbsence->deductible_conges ? 'Oui' : 'Non' }}</li>
                    <li class="list-group-item"><strong>Jours Déductibles Après : </strong>{{ $typeAbsence->jours_deductibles_apres ?? 'N/A' }} jours</li>
                </ul>
                <a href="{{ route('typeAbsences.index') }}" class="btn btn-custom-blue btn-block mt-2">Retour à la liste</a>
            </div>
        </div>
    </div>
</section>
@endsection
