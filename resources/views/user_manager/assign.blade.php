@extends('layouts.template')

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="btn btn-custom-blue btn-block">
            <h2 class="card-title">Assigner un Manager et un Responsable RH à {{ $employee->nom }} {{ $employee->prenom }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('user-manager.assign') }}" method="POST">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                <div class="form-group">
                    <label for="manager_id">Manager :</label>
                    <select name="manager_id" id="manager_id" class="form-control select2">
                        <option value="">Sélectionner un Manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->nom }} {{ $manager->prenom }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="card-footer">
                    <button type="submit" class="btn btn-custom-blue btn-block mt-2">Assigner</button>
                    <a href="{{ route('user-manager.index') }}" class="btn btn-danger mt-2">Retour</a>
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
