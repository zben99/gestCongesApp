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
                    <!-- /.card-header -->
                    @if (isset($departement))
                    <h3 class="card-title">{{ __('Modifier Département') }}</h3>
                    </div>
                    <!-- Le formulaire est géré par la route "posts.update" -->
                    <form method="POST" action="{{ route('departements.update', $departement) }}" enctype="multipart/form-data" >
                        <!-- <input type="hidden" name="_method" value="PUT"> -->
                        @method('PUT')

                    @else
                        <h3 class="card-title">{{ __('Ajouter un Département') }} </h3>
                    </div>
                    <!-- Le formulaire est géré par la route "posts.store" -->
                    <form method="POST" action="{{ route('departements.store') }}" enctype="multipart/form-data" >

                    @endif
                    <!-- form start -->
                    <!-- Le token CSRF -->
                    @csrf


                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name_departement">Nom</label>
                                <input type="text" id="name_departement" name="name_departement" value="{{ isset($departement->name_departement) ? $departement->name_departement : '' }}" class="form-control" required>
                                @error("name_departement")
                                    <div>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="4">{{ isset($departement->description) ? $departement->description : '' }}</textarea>
                                @error("description")
                                    <div>{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-custom-blue btn-block">Enregistrer</button>
                            <a href="{{ route('departements.index') }}" class="btn btn-danger">Retour</a>
                        </div>
                    </div>
                </form>
                </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-1">
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->

@endsection

