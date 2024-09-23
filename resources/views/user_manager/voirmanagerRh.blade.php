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
            <h2 class="card-title">Liste des Managers et leurs Responsables RH Associés</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-over">
                <thead>
                    <tr>
                        <th>Manager</th>
                        <th>Responsable RH</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userManagers as $userManager)
                        <tr>
                            <td>{{ $userManager->manager_nom }} {{ $userManager->manager_prenom }}</td>
                            <td>{{ $userManager->rh_nom }} {{ $userManager->rh_prenom }}</td>
                            <td>
                            <a href="{{ route('user-manager.assign-manager-rh', $userManager->id) }}" class="btn btn-custom-blue btn-block">Assigner Responsable RH</a>
                               
                                <!-- Bouton pour changer ou assigner un responsable RH -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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