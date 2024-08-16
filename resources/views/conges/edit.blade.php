@extends('layouts.template')



@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
          <div class="card">
            <div class="card-header">
              @if(isset($conge))
                <h3 class="card-title">{{ __('Modifier la Demande de Congé') }}</h3>
              </div>
              <form action="{{ route('conges.update', $conge->id) }}" method="POST">
                @method('PUT')
              @else
                <h3 class="card-title">{{ __('Ajouter une Demande de Congé') }}</h3>
              </div>
              <form action="{{ route('conges.store') }}" method="POST">
              @endif
              @csrf
              <div class="card-body">
                <div class="form-group">
                    <label for="userId">Employé</label>
                    <select id="userId" name="userId" class="form-control" required >
                        @if (auth()->user()->profil !== 'responsables RH')
                        <option value="{{ auth()->user()->id }}" {{ isset($conge->userId) && $conge->userId == auth()->user()->id ? 'selected' : '' }}>
                            {{ auth()->user()->nom }} {{ auth()->user()->prenom }}
                        </option>
                        @else

                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ isset($conge->userId) && $conge->userId == $user->id ? 'selected' : '' }}>
                                {{ $user->nom }} {{ $user->prenom }}
                            </option>
                        @endforeach

                        @endif


                    </select>
                    @error('userId')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                <div class="form-group mt-3">
                  <label for="typeConges">Type de Congé</label>
                  <select id="typeConges" name="typeConges" class="form-control" required>
                    <option value="annuels" {{ isset($conge->typeConges) && $conge->typeConges == 'annuels' ? 'selected' : '' }}>Annuel</option>
                    <option value="maladie" {{ isset($conge->typeConges) && $conge->typeConges == 'maladie' ? 'selected' : '' }}>Maladie</option>
                    <option value="maternité" {{ isset($conge->typeConges) && $conge->typeConges == 'maternité' ? 'selected' : '' }}>Maternité</option>
                    <option value="paternité" {{ isset($conge->typeConges) && $conge->typeConges == 'paternité' ? 'selected' : '' }}>Paternité</option>
                  </select>
                  @error('typeConges')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div class="form-group row mt-3">
                  <div class="col">
                    <label for="dateDebut">Date de Début</label>
                    <input type="date" id="dateDebut" name="dateDebut" class="form-control" value="{{ isset($conge->dateDebut) ? $conge->dateDebut : '' }}" required>
                    @error('dateDebut')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="col">
                    <label for="dateFin">Date de Fin</label>
                    <input type="date" id="dateFin" name="dateFin" class="form-control" value="{{ isset($conge->dateFin) ? $conge->dateFin : '' }}" required>
                    @error('dateFin')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>

                <div class="form-group mt-3">
                  <label for="commentaire">Commentaire</label>
                  <textarea id="commentaire" name="commentaire" class="form-control">{{ isset($conge->commentaire) ? $conge->commentaire : '' }}</textarea>
                  @error('commentaire')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('conges.index') }}" class="btn btn-secondary">Retour à la liste</a>
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




