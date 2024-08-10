@extends('layouts.template')

@section('css')
  <!-- Custom CSS for styling -->
  <style>
    .form-container {
      max-width: 600px;
      margin: 0 auto;
    }
    .form-header {
      margin-bottom: 1.5rem;
    }
  </style>
@endsection

@section('content')
  <section class="content">
              <div class="container-fluid">
              <div class="row">
                  <div class="col-12">
                  <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">{{ __('Modifier Département') }}</h3>
                  </div>
                <form action="{{ route('departements.update', $departement->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name_departement">Nom:</label>
                        <input type="text" id="name_departement" name="name_departement" class="form-control" value="{{ $departement->name_departement }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" class="form-control">{{ $departement->description }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
                    <a href="{{ route('departements.index') }}" class="btn btn-secondary mt-3">Retour à la liste</a>
                </form>
                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
@endsection
