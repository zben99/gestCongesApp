@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
  <!-- Custom CSS for status badges -->
@endsection



@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-1">
            </div>
            <div class="col-10">
                <div class="card">
                    <div class="btn btn-custom-blue btn-block">
                        @if (isset($typeConge))
                            <h3 class="card-title">{{ __('Modifier Type de congé') }}</h3>
                        </div>
                        <form method="POST" action="{{ route('typeConges.update', $typeConge) }}" enctype="multipart/form-data">
                            @method('PUT')
                        @else
                            <h3 class="card-title">{{ __('Ajouter un type de congé') }}</h3>
                        </div>
                        <form method="POST" action="{{ route('typeConges.store') }}" enctype="multipart/form-data">
                        @endif
                        @csrf

                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" id="nom" name="nom" value="{{ isset($typeConge->nom) ? $typeConge->nom : '' }}" class="form-control" required>
                                    @error("nom")
                                        <div>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" class="form-control" rows="4">{{ isset($typeConge->description) ? $typeConge->description : '' }}</textarea>
                                    @error("description")
                                        <div>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="duree_max">Durée maximale (en jours)</label>
                                    <input type="number" id="duree_max" name="duree_max" value="{{ isset($typeConge->duree_max) ? $typeConge->duree_max : '' }}" class="form-control" required>
                                    @error("duree_max")
                                        <div>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="justificatif_requis">Justificatif requis</label>
                                    <select id="justificatif_requis" name="justificatif_requis" class="form-control" required>
                                        <option value="0" {{ isset($typeConge->justificatif_requis) && $typeConge->justificatif_requis == 0 ? 'selected' : '' }}>Non</option>
                                        <option value="1" {{ isset($typeConge->justificatif_requis) && $typeConge->justificatif_requis == 1 ? 'selected' : '' }}>Oui</option>
                                    </select>
                                    @error("justificatif_requis")
                                        <div>{{ $message }}</div>
                                    @enderror
                                </div>
                                <br>

                                <button type="submit" class="btn btn-custom-blue btn-block">Enregistrer</button>
                                <a href="{{ route('typeConges.index') }}" class="btn btn-danger">Retour</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-1">
            </div>
        </div>
    </div>
</section>


@endsection

