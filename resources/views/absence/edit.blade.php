@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Select2 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
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
              <h3 class="card-title">{{ isset($absence) ? __('Modifier l\'absence') : __('Ajouter une absence') }}</h3>
            </div>
            <!-- form start -->
            <form action="{{ isset($absence) ? route('absences.update', $absence->id) : route('absences.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              @if(isset($absence))
                @method('PUT')
              @endif
              <div class="card-body">
                @if(!isset($absence)) <!-- Affichage uniquement lors de l'ajout -->
                <div class="form-group">
                  <label for="user_id">{{ __('Employé') }}</label>

                  @if(auth()->user()->profil === 'manager')
                      <select class="form-control" name="UserId" id="user_id">
                          @foreach(auth()->user()->employees as $employee)
                              <option value="{{ $employee->id }}">
                                  {{ $employee->nom }} {{ $employee->prenom }}
                              </option>
                          @endforeach
                      </select>
                  @else
                      <input type="hidden" name="UserId" value="{{ auth()->user()->id }}">
                      <input type="text" class="form-control" value="{{ auth()->user()->nom }} {{ auth()->user()->prenom }}" disabled>
                  @endif
              </div>

                @else <!-- Affichage en mode édition -->
                <div class="form-group">
                  <label for="user_id">{{ __('Employé') }}</label>
                  <input type="text" class="form-control" id="user_id" value="{{ $absence->user->nom }} {{ $absence->user->prenom }}" readonly>
                  <input type="hidden" name="UserId" value="{{ $absence->UserId }}">
                </div>
                @endif

                <div class="form-group">
                  <label for="type_absence_id">{{ __('Type d\'absence') }}</label>
                  <select class="form-control" name="type_absence_id" id="type_absence_id" required>
                    @foreach($typeAbsences as $typeAbsence)
                      <option value="{{ $typeAbsence->id }}"
                        data-justificatif="{{ $typeAbsence->justificatif_requis ? 'true' : 'false' }}"
                        {{ isset($absence) && $absence->type_absence_id == $typeAbsence->id ? 'selected' : '' }}>
                        {{ $typeAbsence->nom }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group" id="justificatif_div" style="display: none;">
                  <label for="justificatif">{{ __('Justificatif (si requis)') }}</label>
                  <input type="file" class="form-control @error('justificatif') is-invalid @enderror" name="justificatif" id="justificatif">
                  @error('justificatif')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="motif">{{ __('Motif') }}</label>
                  <input type="text" class="form-control @error('motif') is-invalid @enderror" name="motif" id="motif" value="{{ isset($absence) ? $absence->motif : old('motif') }}" placeholder="Entrez le motif de l'absence" required>
                  @error('motif')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="dateDebut">{{ __('Date de début') }}</label>
                            <input type="date" class="form-control @error('dateDebut') is-invalid @enderror" name="dateDebut" id="dateDebut" value="{{ isset($absence) ? $absence->dateDebut : old('dateDebut') }}" required>
                            @error('dateDebut')
                              <div class="invalid-feedback">
                                {{ $message }}
                              </div>
                            @enderror
                          </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="dateFin">{{ __('Date de fin') }}</label>
                            <input type="date" class="form-control @error('dateFin') is-invalid @enderror" name="dateFin" id="dateFin" value="{{ isset($absence) ? $absence->dateFin : old('dateFin') }}" required>
                            @error('dateFin')
                              <div class="invalid-feedback">
                                {{ $message }}
                              </div>
                            @enderror
                          </div>
                    </div>
                </div>

                <div class="form-group">
                  <label for="commentaire">{{ __('Commentaire') }}</label>
                  <textarea class="form-control" name="commentaire" id="commentaire" rows="3" placeholder="Ajoutez un commentaire (optionnel)">{{ isset($absence) ? $absence->commentaire : old('commentaire') }}</textarea>
                </div>
                @if(isset($absence))
                <div class="form-group">
                  <label for="status">{{ __('Status') }}</label>
                  <select class="form-control" name="status" id="status" disabled>
                    <option value="en attente" {{ $absence->status == 'en attente' ? 'selected' : '' }}>En attente</option>
                    <option value="approuvé" {{ $absence->status == 'approuvé' ? 'selected' : '' }}>Approuvé</option>
                    <option value="refusé" {{ $absence->status == 'refusé' ? 'selected' : '' }}>Refusé</option>
                  </select>
                </div>
                @endif
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-custom-blue btn-block">{{ isset($absence) ? __('Mettre à jour') : __('Enregistrer') }}</button>
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

@section('script')
<!-- SweetAlert2 -->
<script src="{{ asset('/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('/plugins/toastr/toastr.min.js') }}"></script>

<!-- Gestion de l'affichage du champ justificatif -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const typeAbsenceSelect = document.getElementById('type_absence_id');
    const justificatifDiv = document.getElementById('justificatif_div');

    function toggleJustificatifField() {
      const selectedOption = typeAbsenceSelect.options[typeAbsenceSelect.selectedIndex];
      const justificatifRequis = selectedOption.getAttribute('data-justificatif') === 'true';

      justificatifDiv.style.display = justificatifRequis ? 'block' : 'none';
    }

    typeAbsenceSelect.addEventListener('change', toggleJustificatifField);

    // Initial check on page load
    toggleJustificatifField();
  });
</script>

@if (session('success'))
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });

            @foreach ($errors->all() as $error)
                Toast.fire({
                    icon: 'error',
                    title: '{{ $error }}'
                });
            @endforeach
        });
    </script>
@endif

@if (session('error'))
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        });
    </script>
@endif
@endsection
