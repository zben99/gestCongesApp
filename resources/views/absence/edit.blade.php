@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Select2 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="btn btn-custom-blue btn-block">
              <h3 class="card-title">{{ __('Modifier l\'absence') }}</h3>
            </div>
            <!-- form start -->
            <form action="{{ route('absences.update', $absence->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="user_id">{{ __('Employé') }}</label>
                  <select class="form-control" name="UserId" id="user_id">
                    @foreach($users as $user)
                      <option value="{{ $user->id }}" {{ $user->id == $absence->UserId ? 'selected' : '' }}>
                        {{ $user->nom }} {{ $user->prenom }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="motif">{{ __('Motif') }}</label>
                  <input type="text" class="form-control" name="motif" id="motif" value="{{ $absence->motif }}" required>
                </div>
                <div class="form-group">
                  <label for="dateDebut">{{ __('Date de début') }}</label>
                  <input type="date" class="form-control" name="dateDebut" id="dateDebut" value="{{ $absence->dateDebut }}" required>
                </div>
                <div class="form-group">
                  <label for="dateFin">{{ __('Date de fin') }}</label>
                  <input type="date" class="form-control" name="dateFin" id="dateFin" value="{{ $absence->dateFin }}" required>
                </div>
                <div class="form-group">
                  <label for="commentaire">{{ __('Commentaire') }}</label>
                  <textarea class="form-control" name="commentaire" id="commentaire" rows="3">{{ $absence->commentaire }}</textarea>
                </div>
                <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <select class="form-control" name="status" id="status" disabled>
                  <option value="en attente" {{ $absence->status == 'en attente' ? 'selected' : '' }}>En attente</option>
                  <option value="approuvé" {{ $absence->status == 'approuvé' ? 'selected' : '' }}>Approuvé</option>
                  <option value="refusé" {{ $absence->status == 'refusé' ? 'selected' : '' }}>Refusé</option>
                </select>
              </div>

              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-custom-blue btn-block">{{ __('Mettre à jour') }}</button>
                <a href="{{ route('absences.index') }}" class="btn btn-danger">{{ __('Annuler') }}</a>
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

@section('scripts')
  <!-- Select2 JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#user_id').select2();
    });
  </script>
@endsection
