




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
                <h3 class="card-title">Modification d'un adminstrateur </h3>
            </div>
            <!-- Le formulaire est géré par la route "posts.update" -->
            <form method="POST" action="{{ route('admins.update', $user) }}" enctype="multipart/form-data" >
                <!-- <input type="hidden" name="_method" value="PUT"> -->
                @method('PUT')

            @else
                <h3 class="card-title">Ajout d'un adminstrateur </h3>
            </div>
            <!-- Le formulaire est géré par la route "posts.store" -->
            <form method="POST" action="{{ route('admins.store') }}" enctype="multipart/form-data" >

            @endif
            <!-- form start -->
            <!-- Le token CSRF -->
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" class="form-control" name="name" value="{{ isset($user->name) ? $user->name : old('name') }}"  id="name" placeholder="Nom complet" >

                    @error("name")
                    <div>{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ isset($user->email) ? $user->email : old('email') }}"  id="email" placeholder="Adresse Email" >

                    @error("email")
                    <div>{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" name="password" value=""  id="password"  >

                    @error("password")
                    <div>{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmation de Mot de passe</label>
                    <input type="password" class="form-control" name="password_confirmation"   id="password_confirmation" >
                    @error("password_confirmation")
                    <div>{{ $message }}</div>
                    @enderror
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

