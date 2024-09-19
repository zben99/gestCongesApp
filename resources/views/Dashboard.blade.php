@extends('layouts.template')

@section('css')
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/dashboard.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="container mt-5">
    <h1 class="app-page-title">Tableau de bord</h1>

    <div class="row g-4 mb-4">
        <!-- Card Total Demande Congé -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="app-card app-card-stat shadow-lg h-100 bg-light">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1 text-primary">Total Demandes de Congé</h4>
                    <div class="stats-figure">{{ $totalConges }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->

        <!-- Card Délai d'attente validation Expiré -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="app-card app-card-stat shadow-lg h-100 bg-light">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1 text-warning">Délai d'attente Validation Congé Expiré</h4>
                    <div class="stats-figure">{{ $congesEnAttenteDepuisTroisJours }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->

        <!-- Card Total Congé Validé -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="app-card app-card-stat shadow-lg h-100 bg-light">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1 text-success">Total Congés Validés</h4>
                    <div class="stats-figure">{{ $congesApprouves }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->

        <!-- Card Congé en Attente -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="app-card app-card-stat shadow-lg h-100 bg-light">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1 text-info">Congés en Attente</h4>
                    <div class="stats-figure">{{ $congesEnAttente }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->

        <!-- Card Total Demandes d'Absence -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="app-card app-card-stat shadow-lg h-100 bg-light">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1 text-primary">Total Demandes d'Absence</h4>
                    <div class="stats-figure">{{ $totalDemandesAbsence }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->

        <!-- Card Délai d'attente de validation absence Expiré -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="app-card app-card-stat shadow-lg h-100 bg-light">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1 text-warning">Délai d'attente de Validation Absence Expiré</h4>
                    <div class="stats-figure">{{ $totalAbsencesEnAttenteDepuis3Jours }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->

        <!-- Card Absences Validées -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="app-card app-card-stat shadow-lg h-100 bg-light">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1 text-success">Absences Validées</h4>
                    <div class="stats-figure">{{ $totalAbsencesValides }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->

        <!-- Card Absences en Attente -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="app-card app-card-stat shadow-lg h-100 bg-light">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1 text-info">Absences en Attente</h4>
                    <div class="stats-figure">{{ $totalAbsencesEnAttente }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->

        <!-- Card Absences Rejetées -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="app-card app-card-stat shadow-lg h-100 bg-light">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1 text-danger">Absences Rejetées</h4>
                    <div class="stats-figure">{{ $totalAbsencesrejete }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->

        <!-- Card Congés Rejetés -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="app-card app-card-stat shadow-lg h-100 bg-light">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1 text-danger">Congés Rejetés</h4>
                    <div class="stats-figure">{{ $congerejete }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->
    </div><!--//row-->


@endsection



