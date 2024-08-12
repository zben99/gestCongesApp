
@extends('layouts.template')

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <!-- Custom CSS for status badges -->
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Assigner un Manager à {{ $employee->nom }} {{ $employee->prenom }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('user-manager.assign') }}" method="POST">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                <div class="form-group">
                    <label for="manager_id">Manager :</label>
                    <select name="manager_id" id="manager_id" class="form-control select2">
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->nom }} {{ $manager->prenom }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Assigner Manager</button>
            </form>
        </div>
    </div>
</div>
@endsection
