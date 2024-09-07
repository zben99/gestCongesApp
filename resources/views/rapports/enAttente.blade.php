
@extends('layouts.template')

@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Congés en Attente de Validation</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Nom</th>
                <th>Type de Congé</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($congesEnAttente as $conge)
                <tr>
                  <td>{{ $conge->user->nom }}</td>
                  <td>{{ $conge->typeConge->nom }}</td>
                  <td>{{ $conge->dateDebut->format('d/m/Y') }}</td>
                  <td>{{ $conge->dateFin->format('d/m/Y') }}</td>
                  <td>{{ ucfirst($conge->status) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
@endsection
