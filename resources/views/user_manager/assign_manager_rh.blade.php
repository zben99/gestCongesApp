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
            <h2 class="card-title">Assigner un Manager à un Responsable RH</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('user-manager.assign-manager-rh') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="rh_id">Responsable RH :</label>
                    <select name="rh_id" id="rh_id" class="form-control select2" required>
                        <option value="">Sélectionner un Responsable RH</option>
                        @foreach($rhs as $rh)
                            <option value="{{ $rh->id }}">{{ $rh->nom }} {{ $rh->prenom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-custom-blue btn-block mt-2">Assigner</button>
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

<script>
$(document).ready(function() {
    @if (session('success'))
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,

    });
    @endif

    @if (session('error'))
    Swal.fire({
        icon: 'error',
        title: '{{ session('error') }}',
        showConfirmButton: false,

    });
    @endif
});
</script>
@endsection
