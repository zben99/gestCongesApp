@extends('layouts.template')

@section('css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
@endsection

@section('content')
  <!-- Contenu principal -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{ __('Détails de l\'absence') }}</h3>
              <a href="{{ route('absences.index') }}" class="btn btn-custom-blue btn-sm float-right">
                <i class="fas fa-arrow-left"></i> Retour à la liste
              </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Matricule :</label>
                    <p>{{ $absence->user ? $absence->user->matricule : 'Non disponible' }}</p>
                  </div>
                  <div class="form-group">
                    <label>Nom complet :</label>
                    <p>{{ $absence->user ? $absence->user->prenom . ' ' . $absence->user->nom : 'Non disponible' }}</p>
                  </div>
                  <div class="form-group">
                    <label>Type d'absence :</label>
                    <p>{{ $absence->typeAbsence ? $absence->typeAbsence->nom : 'Non disponible' }}</p>
                  </div>
                  <div class="form-group">
                    <label>Motif :</label>
                    <p>{{ $absence->motif }}</p>
                  </div>
                  <div class="form-group">
                    <label>Date de début :</label>
                    <p>{{ $absence->dateDebut->format('d/m/Y') }}</p>
                  </div>
                  <div class="form-group">
                    <label>Date de fin :</label>
                    <p>{{ $absence->dateFin->format('d/m/Y') }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Justificatif :</label>
                    @if ($absence->justificatif)
                      <a href="{{ asset('storage/justificatifs/' . $absence->justificatif) }}" target="_blank" class="btn btn-custom-blue btn-icon">
                        <i class="fas fa-file"></i> Voir le justificatif
                      </a>
                    @else
                      <p>Aucun justificatif</p>
                    @endif
                  </div>
                  <div class="form-group">
                    <label>Statut :</label>
                    <span class="
                      @if ($absence->status === 'en attente') status-pending
                      @elseif ($absence->status === 'approuvé') status-approved
                      @elseif ($absence->status === 'refusé') status-rejected
                      @endif
                    ">
                      {{ ucfirst($absence->status) }}
                    </span>
                  </div>
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="{{ asset('/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('/plugins/toastr/toastr.min.js') }}"></script>

@if (session('success'))
    <script>
        $(function() {
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
        });
    </script>
@endif

@if (session('error'))
    <script>
        $(function() {
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
        });
    </script>
@endif
@endsection
