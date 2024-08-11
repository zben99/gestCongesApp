
@extends('layouts.template')

@section('content')
<div class="container">
    <h1>Gérer les relations Manager-Employé</h1>

    <form action="{{ route('user-manager.assign') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="employee_id">Employé :</label>
            <select name="employee_id" id="employee_id" class="form-control">
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->nom }} {{ $employee->prenom }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="manager_id">Manager :</label>
            <select name="manager_id" id="manager_id" class="form-control">
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->nom }} {{ $manager->prenom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Assigner Manager</button>
    </form>

    <hr>

    <h2>Retirer un manager</h2>
    <form action="{{ route('user-manager.remove') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="employee_id">Employé :</label>
            <select name="employee_id" id="employee_id" class="form-control">
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->nom }} {{ $employee->prenom }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="manager_id">Manager :</label>
            <select name="manager_id" id="manager_id" class="form-control">
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->nom }} {{ $manager->prenom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-danger">Retirer Manager</button>
    </form>
</div>
@endsection
