@extends('layouts.template')

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="btn btn-custom-blue btn-block">
            <h2 class="card-title">Changer le Responsable RH pour le Manager</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('user-manager.change-manager-rh', $manager->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Sélection du Manager -->
                <div class="form-group">
                    <label for="manager_id">Manager :</label>
                    <select name="manager_id" id="manager_id" class="form-control select2" required>
                        <option value="">Sélectionner un Manager</option>
                        <option value="{{ $manager->id }}" selected>
                            {{ $manager->nom }} {{ $manager->prenom }}
                        </option>
                        @foreach($managers as $mgr)
                            <option value="{{ $mgr->id }}">
                                {{ $mgr->nom }} {{ $mgr->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sélection du nouveau Responsable RH -->
                <div class="form-group">
                    <label for="rh_id">Nouveau Responsable RH :</label>
                    <select name="rh_id" id="rh_id" class="form-control select2">
                        <option value="">Sélectionner un Responsable RH</option>
                        @foreach($rhs as $rh)
                            <option value="{{ $rh->id }}" {{ $currentRh == $rh->id ? 'selected' : '' }}>
                                {{ $rh->nom }} {{ $rh->prenom }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('rh_id'))
                        <span class="text-danger">{{ $errors->first('rh_id') }}</span>
                    @endif
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-custom-blue btn-block mt-2">Changer Responsable RH</button>
                    <a href="{{ route('user-manager.voirmanagerRh') }}" class="btn btn-danger mt-2">Retour</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Sélectionner un élément',
        allowClear: true
    });

    @if (session('success'))
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000
    });
    @endif

    @if (session('error'))
    Swal.fire({
        icon: 'error',
        title: '{{ session('error') }}',
        showConfirmButton: false,
        timer: 3000
    });
    @endif
});
</script>
@endsection
