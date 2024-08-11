@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Select2 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
                  <label for="UserId">{{ __('Sélectionner un employé') }}</label>
                  <select class="form-control select2 @error('UserId') is-invalid @enderror" name="UserId" id="UserId" required>
                    <option value="" disabled selected>{{ __('-- Choisissez un employé --') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('UserId') == $user->id ? 'selected' : '' }}>
                            {{ $user->nom }} {{ $user->prenom }}
                        </option>
                    @endforeach
                  </select>
                  @error('UserId')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="motif">{{ __('Motif') }}</label>
                  <input type="text" class="form-control @error('motif') is-invalid @enderror" name="motif" id="motif" placeholder="Entrez le motif de l'absence" required>
                  @error('motif')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="dateDebut">{{ __('Date de début') }}</label>
                  <input type="date" class="form-control @error('dateDebut') is-invalid @enderror" name="dateDebut" id="dateDebut" required>
                  @error('dateDebut')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="dateFin">{{ __('Date de fin') }}</label>
                  <input type="date" class="form-control @error('dateFin') is-invalid @enderror" name="dateFin" id="dateFin" required>
                  @error('dateFin')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="commentaire">{{ __('Commentaire') }}</label>
                  <textarea class="form-control" name="commentaire" id="commentaire" rows="3" placeholder="Ajoutez un commentaire (optionnel)"></textarea>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">

              <input type="submit" class="btn btn-primary" name="valider" value="Enregistrer" >

              <a href="{{ route('admins.index') }}" class="btn btn-danger">
                      Retour
              </a>

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

  <script type="text/javascript">
    $(document).ready(function() {
        $('#user_id').select2({
            placeholder: '-- Choisissez un employé --',
            allowClear: true,
            width: '100%'
        });
    });
  </script>
@endsection
