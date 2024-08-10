

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
                      <label for="inputName" class="col-sm-4 col-form-label">Nom complet</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputName" name="name" value="{{ isset($user->name) ? $user->name : old('name') }}" placeholder="Name">
                      </div>
                      <x-input-error class="mt-2 text-red" :messages="$errors->get('name')" />
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
                      <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmail" name="email" value="{{ isset($user->email) ? $user->email : old('email') }}" placeholder="Email">
                      </div>
                      <x-input-error class="mt-2 text-red" :messages="$errors->get('email')" />

                    </div>


                    <div class="form-group row">
                      <div class="offset-sm-4 col-sm-8">
                        <button type="submit" class="btn btn-danger">Enregistrer</button>
                      </div>
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



                    <div class="form-group row">
                      <label for="current_password" class="col-sm-4 col-form-label">Mot de passe actuel</label>
                      <div class="col-sm-8">
                        <input type="password" class="form-control" id="current_password" name="current_password" >
                      </div>
                      <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2  text-red" />
                        @error("current_password")
                            <div>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                      <label for="password" class="col-sm-4 col-form-label">Nouveau Mot de passe</label>
                      <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" name="password" >
                      </div>
                      <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2  text-red" />
                      @error("password")
                        <div>{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="form-group row">
                        <label for="password_confirmation" class="col-sm-4 col-form-label">Confirmation de mot de passe</label>
                        <div class="col-sm-8">
                          <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"  >
                        </div>
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red" />
                        @error("password_confirmation")
                          <div>{{ $message }}</div>
                        @enderror
                      </div>


                    <div class="form-group row">
                      <div class="offset-sm-4 col-sm-10">
                        <button type="submit" class="btn btn-danger">Enregistrer</button>
                      </div>
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
