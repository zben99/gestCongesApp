
@extends('layouts.template')

@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Départements avec Plus de Congés</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Département</th>
                <th>Nombre de Congés</th>
              </tr>
            </thead>
            <tbody>
              @foreach($departementsAvecConge as $department => $count)
                <tr>
                  <td>{{ $department }}</td>
                  <td>{{ $count }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
@endsection
