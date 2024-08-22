@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                <div class="card">
                    <div class="btn btn-custom-blue btn-block">
                        @if (isset($typeAbsence))
                            <h3 class="card-title">{{ __('Modifier Type d\'absence') }}</h3>
                        </div>
                        <form method="POST" action="{{ route('typeAbsences.update', $typeAbsence) }}" enctype="multipart/form-data">
                            @method('PUT')
                        @else
                            <h3 class="card-title">{{ __('Ajouter un type d\'absence') }}</h3>
                        </div>
                        <form method="POST" action="{{ route('typeAbsences.store') }}" enctype="multipart/form-data">
                        @endif
                        @csrf

                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" id="nom" name="nom" value="{{ isset($typeAbsence->nom) ? $typeAbsence->nom : '' }}" class="form-control" required>
                                    @error("nom")
                                        <div>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" class="form-control" rows="4">{{ isset($typeAbsence->description) ? $typeAbsence->description : '' }}</textarea>
                                    @error("description")
                                        <div>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="duree_max">Durée maximale (en jours)</label>
                                    <input type="number" id="duree_max" name="duree_max" value="{{ isset($typeAbsence->duree_max) ? $typeAbsence->duree_max : '' }}" class="form-control" required>
                                    @error("duree_max")
                                        <div>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="justificatif_requis">Justificatif requis</label>
                                    <select id="justificatif_requis" name="justificatif_requis" class="form-control" required>
                                        <option value="0" {{ isset($typeAbsence->justificatif_requis) && $typeAbsence->justificatif_requis == 0 ? 'selected' : '' }}>Non</option>
                                        <option value="1" {{ isset($typeAbsence->justificatif_requis) && $typeAbsence->justificatif_requis == 1 ? 'selected' : '' }}>Oui</option>
                                    </select>
                                    @error("justificatif_requis")
                                        <div>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="deductible_conges">Déductible des congés</label>
                                    <select id="deductible_conges" name="deductible_conges" class="form-control" required onchange="toggleJoursDeductibles()">
                                        <option value="0" {{ isset($typeAbsence->deductible_conges) && $typeAbsence->deductible_conges == 0 ? 'selected' : '' }}>Non</option>
                                        <option value="1" {{ isset($typeAbsence->deductible_conges) && $typeAbsence->deductible_conges == 1 ? 'selected' : '' }}>Oui</option>
                                    </select>
                                    @error("deductible_conges")
                                        <div>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="jours_deductibles_apres">Jours déductibles après</label>
                                    <input type="number" id="jours_deductibles_apres" name="jours_deductibles_apres" value="{{ isset($typeAbsence->jours_deductibles_apres) ? $typeAbsence->jours_deductibles_apres : '' }}" class="form-control" {{ isset($typeAbsence->deductible_conges) && $typeAbsence->deductible_conges == 0 ? 'disabled' : '' }}>
                                    @error("jours_deductibles_apres")
                                        <div>{{ $message }}</div>
                                    @enderror
                                </div>
                                <br>
                                <button type="submit" class="btn btn-custom-blue btn-block">Enregistrer</button>
                                <a href="{{ route('typeAbsences.index') }}" class="btn btn-danger">Retour</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    function toggleJoursDeductibles() {
        var deductibleConges = document.getElementById('deductible_conges').value;
        var joursDeductiblesApres = document.getElementById('jours_deductibles_apres');

        if (deductibleConges == '0') {
            joursDeductiblesApres.value = ''; // Réinitialiser la valeur
            joursDeductiblesApres.disabled = true;
        } else {
            joursDeductiblesApres.disabled = false;
        }
    }

    // Initialiser l'état du champ "Jours déductibles après" au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        toggleJoursDeductibles();
    });
</script>
@endsection
