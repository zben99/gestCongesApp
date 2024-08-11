@extends('layouts.template')

@section('content')

<br>

<section class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informations de Profil</h3>
                    </div>
                    <div class="card-body">

                        @if (session('status') === 'profile-updated')
                        <div class="alert alert-success" role="alert">
                            Enregistrement effectué avec succès !
                        </div>
                        @endif

                        <form method="post" action="{{ route('profile.update') }}" class="form-horizontal">
                            @csrf
                            @method('patch')

                            <div class="form-group row">
                                <div class="col">
                                    <label for="inputName" class=" col-form-label">Nom </label>
                                    <input type="text" class="form-control" id="inputName" name="nom"
                                            value="{{ isset($user->nom) ? $user->nom : old('nom') }}" placeholder="Nom">

                                    <x-input-error class="mt-2 text-red" :messages="$errors->get('nom')" />
                                </div>
                                <div class="col">
                                    <label for="inputPrenom" class=" col-form-label">Prénom(s)</label>

                                    <input type="text" class="form-control" id="inputPrenom" name="prenom"
                                        value="{{ isset($user->prenom) ? $user->prenom : old('prenom') }}" placeholder="Prénom(s)">

                                <x-input-error class="mt-2 text-red" :messages="$errors->get('prenom')" />
                                </div>

                            </div>



                            <div class="form-group row">
                                <div class="col">
                                    <label for="inputEmail" class=" col-form-label">Email</label>

                                    <input type="email" class="form-control" id="inputEmail" name="email"
                                        value="{{ isset($user->email) ? $user->email : old('email') }}" placeholder="Email">

                                   <x-input-error class="mt-2 text-red" :messages="$errors->get('email')" />
                                </div>
                                <div class="col">
                                    <label for="inputBirthDate" class=" col-form-label">Date de Naissance</label>

                                    <input type="date" class="form-control" id="inputBirthDate" name="birth_date"
                                        value="{{ isset($user->birth_date) ? \Illuminate\Support\Carbon::parse($user->birth_date)->format('Y-m-d') : old('birth_date') }}">

                                     <x-input-error class="mt-2 text-red" :messages="$errors->get('birth_date')" />
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col">
                                    <label for="inputTelephone1" class=" col-form-label">Téléphone 1</label>

                                    <input type="text" class="form-control" id="inputTelephone1" name="telephone1"
                                        value="{{ isset($user->telephone1) ? $user->telephone1 : old('telephone1') }}"
                                        placeholder="Téléphone 1">

                                     <x-input-error class="mt-2 text-red" :messages="$errors->get('telephone1')" />
                                </div>

                                <div class="col">

                                    <label for="inputTelephone2" class=" col-form-label">Téléphone 2</label>

                                    <input type="text" class="form-control" id="inputTelephone2" name="telephone2"
                                        value="{{ isset($user->telephone2) ? $user->telephone2 : old('telephone2') }}"
                                        placeholder="Téléphone 2">

                                <x-input-error class="mt-2 text-red" :messages="$errors->get('telephone2')" />
                                </div>
                            </div>






                            <div class="form-group ">

                                    <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>

                            </div>

                        </form>

                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-9">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">Mettre à jour le Mot de Passe</h3>
                    </div>

                    <div class="card-body">

                        @if (session('status') === 'password-updated')
                        <div class="alert alert-success" role="alert">
                            Enregistrement effectué avec succès !
                        </div>
                        @endif

                        <form method="post" action="{{ route('password.update') }}" class="form-horizontal">
                            @csrf
                            @method('put')

                            <div class="form-group ">
                                <label for="current_password" class=" col-form-label">Mot de passe actuel</label>

                                    <input type="password" class="form-control" id="current_password" name="current_password">

                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red" />
                                @error("current_password")
                                <div>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group ">
                                <label for="password" class=" col-form-label">Nouveau Mot de passe</label>

                                    <input type="password" class="form-control" id="password" name="password">

                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red" />
                                @error("password")
                                <div>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group ">
                                <label for="password_confirmation" class="col-form-label">Confirmation de mot de passe</label>

                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">

                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red" />
                                @error("password_confirmation")
                                <div>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group ">

                                    <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>

                            </div>

                        </form>

                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

@endsection
