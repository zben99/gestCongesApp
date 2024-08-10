
@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">{{ __('Ajouter une absence') }}</h3>
            </div>
            <!-- form start -->
            <form action="{{ route('absences.store') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="user_id">{{ __('Employé') }}</label>
                  <select class="form-control" name="user_id" id="user_id">
                    @foreach($users as $user)
                      <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="motif">{{ __('Motif') }}</label>
                  <input type="text" class="form-control" name="motif" id="motif" placeholder="Entrez le motif de l'absence" required>
                </div>
                <div class="form-group">
                  <label for="dateDebut">{{ __('Date de début') }}</label>
                  <input type="date" class="form-control" name="dateDebut" id="dateDebut" required>
                </div>
                <div class="form-group">
                  <label for="dateFin">{{ __('Date de fin') }}</label>
                  <input type="date" class="form-control" name="dateFin" id="dateFin" required>
                </div>
                <div class="form-group">
                  <label for="commentaire">{{ __('Commentaire') }}</label>
                  <textarea class="form-control" name="commentaire" id="commentaire" rows="3" placeholder="Ajoutez un commentaire (optionnel)"></textarea>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ __('Enregistrer') }}</button>
                <a href="{{ route('absences.index') }}" class="btn btn-secondary">{{ __('Annuler') }}</a>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
