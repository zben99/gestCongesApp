@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
@endsection

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
          <div class="card">
            <div class="btn btn-custom-blue btn-block">


              @if(isset($rappel))
              <h3 class="card-title">{{ __('Modifier le Rappel') }}</h3>
              </div>
                <form action="{{ route('rappels.update', [$conge->id, $rappel->id]) }}" method="POST">
                    @method('PUT')
              @else
              <h3 class="card-title">{{ __('Ajouter un Rappel') }}</h3>
              </div>
                <form action="{{ route('rappels.store', ['conge' => $conge->id]) }}" method="POST">
             @endif
              @csrf
              <div class="card-body">


                <!-- Date de début du rappel -->
                <div class="form-group mt-3">
                  <label for="dateDebutRappel">Date de Début du Rappel</label>
                  <input type="date" id="dateDebutRappel" name="dateDebutRappel" class="form-control" value="{{ isset($rappel->dateDebutRappel) ? $rappel->dateDebutRappel : '' }}" required>
                  @error('dateDebutRappel')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <!-- Date de fin du rappel (optionnelle) -->
                <div class="form-group mt-3">
                  <label for="dateFinRappel">Date de Fin du Rappel (Optionnel)</label>
                  <input type="date" id="dateFinRappel" name="dateFinRappel" class="form-control" value="{{ isset($rappel->dateFinRappel) ? $rappel->dateFinRappel : '' }}">
                  @error('dateFinRappel')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <!-- Commentaire -->
                <div class="form-group mt-3">
                  <label for="commentaire">Commentaire</label>
                  <textarea id="commentaire" name="commentaire" class="form-control">{{ isset($rappel->commentaire) ? $rappel->commentaire : '' }}</textarea>
                  @error('commentaire')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-custom-blue btn-block">Enregistrer</button>
                <a href="{{ route('rappels.index', $conge->id) }}" class="btn btn-danger">Retour à la liste</a>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
        <div class="col-1"></div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
