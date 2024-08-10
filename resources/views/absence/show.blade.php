
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
              <h3 class="card-title">{{ __('Détails de l\'absence') }}</h3>
            </div>
            <!-- card body start -->
            <div class="card-body">
              <div class="form-group">
                <label for="user_id">{{ __('Employé') }}</label>
                <input type="text" class="form-control" id="user_id" value="{{ $absence->user->name }}" readonly>
              </div>
              <div class="form-group">
                <label for="motif">{{ __('Motif') }}</label>
                <input type="text" class="form-control" id="motif" value="{{ $absence->motif }}" readonly>
              </div>
              <div class="form-group">
                <label for="dateDebut">{{ __('Date de début') }}</label>
                <input type="date" class="form-control" id="dateDebut" value="{{ $absence->dateDebut }}" readonly>
              </div>
              <div class="form-group">
                <label for="dateFin">{{ __('Date de fin') }}</label>
                <input type="date" class="form-control" id="dateFin" value="{{ $absence->dateFin }}" readonly>
              </div>
              <div class="form-group">
                <label for="commentaire">{{ __('Commentaire') }}</label>
                <textarea class="form-control" id="commentaire" rows="3" readonly>{{ $absence->commentaire }}</textarea>
              </div>
              <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <input type="text" class="form-control" id="status" value="{{ ucfirst($absence->status) }}" readonly>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <a href="{{ route('absences.index') }}" class="btn btn-secondary">{{ __('Retour') }}</a>
            </div>
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
