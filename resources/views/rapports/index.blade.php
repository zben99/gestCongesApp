
@extends('layouts.template')

@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Rapports de Congés</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <a href="{{ route('rapports.enCours') }}" class="btn btn-primary btn-block">Congés Actuels</a>
            </div>
            <div class="col-md-4">
              <a href="{{ route('rapports.moisProchain') }}" class="btn btn-secondary btn-block">Congés du Mois Prochain</a>
            </div>
            <div class="col-md-4">
              <a href="{{ route('rapports.enAttente') }}" class="btn btn-info btn-block">Congés en Attente</a>
            </div>
            <div class="col-md-4 mt-2">
              <a href="{{ route('rapports.departements') }}" class="btn btn-warning btn-block">Départements avec Plus de Congés</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
