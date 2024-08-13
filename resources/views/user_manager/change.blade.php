@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select3.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Changer le Manager</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('user-manager.change') }}" method="POST">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                <div class="form-group">
                    <label for="manager_id">Nouveau Manager :</label>
                    <select name="manager_id" id="manager_id" class="form-control select2">
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->nom }} {{ $manager->prenom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Changer Manager</button>
                    <a href="{{ route('user-manager.index') }}" class="btn btn-secondary">Retour à la liste</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="{{ asset('/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('/plugins/toastr/toastr.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
  $(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
      theme: 'bootstrap4',
      placeholder: 'Sélectionner un élément',
      allowClear: true
    });

    @if (session('success'))
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}'
    });
    @endif

    @if (session('error'))
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    Toast.fire({
        icon: 'error',
        title: '{{ session('error') }}'
    });
    @endif
  });
</script>
@endsection
