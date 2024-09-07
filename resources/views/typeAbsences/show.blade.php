
@extends('layouts.template')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
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
                <a href="{{ route('typeAbsences.index') }}" class="btn btn-primary mt-3">Retour à la liste</a>
            </div>
        </div>
    </div>
</section>
@endsection
