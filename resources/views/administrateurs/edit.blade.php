




@extends('layouts.template')


@section('content')
<br>
<section class="content">
<div class="row">
    <!-- left column -->
    <div class="col-md-1">
    </div>
    <div class="col-md-10">
        <div class="card card-primary">
            <div class="card-header">

            <!-- /.card-header -->
            @if (isset($user))
                <h3 class="card-title">Modification d'un utilisateur </h3>
            </div>
            <!-- Le formulaire est géré par la route "posts.update" -->
            <form method="POST" action="{{ route('admins.update', $user) }}" enctype="multipart/form-data" >
                <!-- <input type="hidden" name="_method" value="PUT"> -->
                @method('PUT')

            @else
                <h3 class="card-title">Ajout d'un utilisateur </h3>
            </div>
            <!-- Le formulaire est géré par la route "posts.store" -->
            <form method="POST" action="{{ route('admins.store') }}" enctype="multipart/form-data" >

            @endif
            <!-- form start -->
            <!-- Le token CSRF -->
            @csrf




            <div class="card-body">

                <div class="form-group row">
                    <div class="col">
                        <label for="matricule">Matricule</label>
                        <input type="text" id="matricule" name="matricule" class="form-control" value="{{ isset($user->matricule) ? $user->matricule : '' }}" required>
                        @error("matricule")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col"></div>

                </div>
                <hr>
                <div class="form-group row">
                    <div class="col">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control" value="{{ isset($user->nom) ? $user->nom : '' }}" required>
                        @error("nom")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="prenom">Prénom(s)</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" value="{{ isset($user->prenom) ? $user->prenom : '' }}" required>
                        @error("prenom")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <hr>

                <div class="form-group row">
                    <div class="col">
                        <label for="email">Email </label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ isset($user->email) ? $user->email : '' }}" required>
                        @error("email")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <hr>
                <div class="form-group row">
                    <div class="col">
                        <label for="telephone1">Téléphone 1</label>
                        <input type="text" id="telephone1" name="telephone1" class="form-control" value="{{ isset($user->telephone1) ? $user->telephone1 : '' }}" required>
                        @error("telephone1")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="telephone2">Téléphone 2</label>
                        <input type="text" id="telephone2" name="telephone2" class="form-control" value="{{ isset($user->telephone2) ? $user->telephone2 : '' }}">
                        @error("telephone2")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <hr>
                <div class="form-group row">
                    <div class="col">
                        <label for="birthDate">Date de Naissance</label>
                        <input type="date" id="	birthDate" name="birthDate" class="form-control"
                         value="{{ isset($user->birth_date) ? \Illuminate\Support\Carbon::parse($user->birth_date)->format('Y-m-d') : '' }}" required>
                        @error("birthDate")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="profil">Profil</label>
                        <select id="profil" name="profil" class="form-control" required>
                            <option value=""></option>
                            <option value="employés" {{ isset($user->profil) && $user->profil == 'employés' ? 'selected' : '' }}>Employés</option>
                            <option value="manager" {{ isset($user->profil) && $user->profil == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="responsables RH" {{ isset($user->profil) && $user->profil == 'responsables RH' ? 'selected' : '' }}>Responsables RH</option>
                            <option value="administrateurs" {{ isset($user->profil) && $user->profil == 'administrateurs' ? 'selected' : '' }}>Administrateurs</option>
                        </select>
                        @error("profil")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <hr>

                <div class="form-group row">
                    <div class="col">
                        <label for="departementId">Département</label>
                        <select id="departementId" name="departementId" class="form-control" >
                            <option value=""></option>
                            @foreach ($departements as $departement)
                                <option value="{{ $departement->id }}" {{ isset($user->departementId) && $user->departementId == $departement->id ? 'selected' : '' }}>
                                    {{ $departement->name_departement }}
                                </option>
                            @endforeach
                        </select>
                        @error("departementId")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="posteId">Poste</label>
                        <select id="posteId" name="posteId" class="form-control" >
                            <option value=""></option>
                            @foreach ($postes as $poste)
                                <option value="{{ $poste->id }}" {{ isset($user->posteId) && $user->posteId == $poste->id ? 'selected' : '' }}>
                                    {{ $poste->name_poste }}
                                </option>
                            @endforeach
                        </select>
                        @error("posteId")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <hr>

                <div class="form-group row ">
                    <div class="col">
                        <label for="arrivalDate">Date d'Arrivée</label>
                        <input type="date" id="arrivalDate" name="arrivalDate" class="form-control"
                               value="{{ isset($user->arrival_date) ? \Illuminate\Support\Carbon::parse($user->arrival_date)->format('Y-m-d') : '' }}" required>
                        @error("arrivalDate")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="initializationDate">Date d'initialisation</label>
                        <input type="date" id="initializationDate" name="initializationDate" class="form-control"
                               value="{{ isset($user->initialization_date) ? \Illuminate\Support\Carbon::parse($user->initialization_date)->format('Y-m-d') : '' }}" required>
                        @error("initializationDate")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <div class="form-group row ">
                    <div class="col">
                        <label for="initial">Initialisation congés</label>
                        <input type="number" id="initial" name="initial" class="form-control" value="{{ isset($user->initial) ? $user->initial : '' }}" required>
                        @error("initial")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">

                    </div>

                </div>

                <hr>

                <div class="form-group row">
                    <div class="col">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" name="password" value=""  id="password"  >

                        @error("password")
                        <div>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="password_confirmation">Confirmation de Mot de passe</label>
                        <input type="password" class="form-control" name="password_confirmation"   id="password_confirmation" >
                        @error("password_confirmation")
                        <div>{{ $message }}</div>
                        @enderror
                    </div>

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
    </div>




    <div class="col-md-1">
    </div>

</div>

</div>



@endsection

