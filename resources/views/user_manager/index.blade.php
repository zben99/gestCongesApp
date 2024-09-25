@extends('layouts.template')

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="btn btn-custom-blue btn-block">
            <h2 class="card-title">Administration||Workflow</h2>
        </div>
        <div class="card-body">
            <!-- Formulaire de recherche -->
            <form action="{{ route('user-manager.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                <input type="text" name="search" class="form-control search-field" placeholder="Rechercher par matricule, nom ou prénom" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-custom-blue btn-block" >Rechercher</button>
                    </div>
                </div>
            </form>

            <!-- Tableau des employés -->
            <table class="table table-bordered table-over">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Profil</th>
                        <th>Manager</th> <!-- Nouvelle colonne -->
                        <th>Responsable RH</th> <!-- Nouvelle colonne -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>{{ $employee->matricule }}</td>
                        <td>{{ $employee->nom }}</td>
                        <td>{{ $employee->prenom }}</td>
                        <td>{{ $employee->profil }}</td>
                        <td>
                            @if($employee->manager!=null)
                                {{ $employee->manager->nom }} {{ $employee->manager->prenom }}
                            @else
                                @if ($employee->profil=="manager")
                                    {{ $employee->nom }} {{ $employee->prenom }}
                                @else
                                    Aucun manager assigné
                                @endif

                            @endif


                            </td> <!-- Affichage du manager -->
                            <td>

                                @if ($employee->profil=="employés")
                                    @if($employee->manager!=null and $employee->manager->rh!=null)
                                        {{ $employee->manager->rh->nom }} {{ $employee->manager->rh->prenom }}

                                    @else

                                    Aucun Responsable RH assigné
                                    @endif
                                @else

                                    @if($employee->rh!=null)
                                        {{ $employee->rh->nom }} {{ $employee->rh->prenom }}
                                    @else

                                    Aucun Responsable RH assigné

                                    @endif

                                @endif

                            </td>

                        <td>
                            <!-- Liens pour assigner ou changer manager et responsable RH -->
                            @if ($employee->profil=="employés")
                                <button class="btn btn-info btn-sm assign-manager" data-id="{{ $employee->id }}" data-name="{{ $employee->nom }} {{ $employee->prenom }}">Assigner Manager</button>
                            @endif
                            @if ($employee->profil=="manager")
                                <button class="btn btn-warning btn-sm assign-rh" data-id="{{ $employee->id }}" data-name="{{ $employee->nom }} {{ $employee->prenom }}">Assigner RH</button>
                            @endif
                        </td>


                    </tr>
                @endforeach
                </tbody>
            </table>
                 <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
            {{ $employees->links('vendor.pagination.custom') }}
         </div>
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

<script>
    var managers = {!! json_encode($managersFormatted) !!};
    var rhs = {!! json_encode($rhsFormatted) !!};
</script>

<script>
    $(document).ready(function() {
        // Gestionnaire d'événements pour le bouton "Assigner Manager"
        $('.assign-manager').click(function() {
            var employeeId = $(this).data('id');
            var employeeName = $(this).data('name');



            Swal.fire({
                title: 'Assigner un Manager',
                text: 'Sélectionnez un manager pour ' + employeeName,
                input: 'select',
                inputOptions: managers,
                showCancelButton: true,
                confirmButtonText: 'Assigner',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Effectuer une requête AJAX pour assigner le manager
                    $.ajax({
                        url: '{{ route('user-manager.assign') }}', // Remplacez par votre route
                        method: 'POST',
                        data: {
                            employee_id: employeeId,
                            manager_id: result.value,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Succès!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false, // Masquer le bouton de confirmation
                                timer: 1500, // Durée en millisecondes
                            }).then(() => {
                                location.reload(); // Recharger la page après la fermeture du popup
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Erreur!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });

        // Gestionnaire d'événements pour le bouton "Assigner RH"
        $('.assign-rh').click(function() {
            var employeeId = $(this).data('id');
            var employeeName = $(this).data('name');

            Swal.fire({
                title: 'Assigner un Responsable RH',
                text: 'Sélectionnez un Responsable RH pour ' + employeeName,
                input: 'select',
                inputOptions: rhs,
                showCancelButton: true,
                confirmButtonText: 'Assigner',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Effectuer une requête AJAX pour assigner le RH
                    $.ajax({
                        url: '{{ route('user-manager.assign-manager-rh') }}', // Remplacez par votre route
                        method: 'POST',
                        data: {
                            employee_id: employeeId,
                            rh_id: result.value,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Succès!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false, // Masquer le bouton de confirmation
                                timer: 1500, // Durée en millisecondes
                            }).then(() => {
                                location.reload(); // Recharger la page après la fermeture du popup
                            });

                        },
                        error: function(xhr) {
                            Swal.fire('Erreur!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });
    });
    </script>

@endsection



