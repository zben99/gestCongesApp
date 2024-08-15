
//Contenu de mon dashboard

@extends('layouts.template');
@section('content')

<h1 class="app-page-title" style="color: blue;">Dashboard</h1>

			    <div class="row g-4 mb-4">
				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1" style="color: blue;">Total demande Congé</h4>
							    <div class="stats-figure">{{ $totalConges }}</div>
							    <div class="stats-meta">
								</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->

				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1" style="color: blue;">Délai d'attente validation Expiré</h4>							<div class="stats-figure">{{$congesEnAttenteDepuisTroisJours}}</div>
							    <div class="stats-meta">
								</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->

				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1" style="color: blue;">Toatl Conge Valider</h4>
							    <div class="stats-figure">{{ $congesApprouves }}</div>
							    <div class="stats-meta">
								    Fermer</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->

				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1" style="color: blue;">Attente Validation Congé</h4>
							    <div class="stats-figure">{{$congesEnAttente}}</div>
							    <div class="stats-meta">Ouvert</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->

					<div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1" style="color: blue;">Attente Validation Congé</h4>
							    <div class="stats-figure">{{ $congerejete }}</div>
							    <div class="stats-meta">Fermer</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->
			   

                
				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1 text-primary">Total Demande Absence</h4>
							    <div class="stats-figure">{{ $totalDemandesAbsence }}</div>
							    <div class="stats-meta ">
								</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->

				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1 text-primary">Total Absence valider</h4>
							    <div class="stats-figure">{{ $totalAbsencesValides }}</div>
								<div class="stats-meta">
								Fermer</div>
							    <div class="stats-meta">
								    </div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->

				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1 text-primary">Total Absence en attente validation</h4>
							    <div class="stats-figure">{{ $totalAbsencesEnAttente }}</div>
							    <div class="stats-meta">
								    Ouvert</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->

					<div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1 text-primary">Absence rejeter </h4>
							    <div class="stats-figure">{{ $totalAbsencesrejete}}</div>
							    <div class="stats-meta">New</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->

				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1 text-primary">Delai attente expiré </h4>
							    <div class="stats-figure">{{ $totalAbsencesEnAttenteDepuis3Jours}}</div>
							    <div class="stats-meta">New</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->

			    </div><!--//row-->



@endsection
