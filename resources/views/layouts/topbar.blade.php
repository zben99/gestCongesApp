<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <link rel="stylesheet" href="path/to/fontawesome.min.css">
	<link id="theme-style" rel="stylesheet" href="assets/css/topbar.css">
	<link rel="stylesheet" href="{{ asset('/plugins/toastr/persostyle.css') }}">
</head>
<body>
	<div class="app-header-inner">
		<div class="container-fluid py-2">
			<div class="app-header-content">
				<div class="row justify-content-between align-items-center">

					<div class="col-auto">
						<a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
							<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
								<title>Menu</title>
								<path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
							</svg>
						</a>
					</div><!--//col-->

					<div class="app-utilities col-auto">
						<!-- Suppression des notifications et des paramètres -->

						<!-- Section utilisateur avec affichage du nom de l'utilisateur connecté -->
						<div class="app-utility-item app-user-dropdown dropdown">
							<a class="dropdown-toggle user-info" id="user-dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
								{{ Auth::user()->nom }} {{ Auth::user()->prenom }}
							</a>
							<ul class="dropdown-menu" aria-labelledby="user-dropdown-toggle">
								<li><a href="{{ route('profile.edit') }}" class="d-block"><i class="fas fa-edit"></i> Mon profil </a></li>
								<li><hr class="dropdown-divider"></li>
								<li>
									<a class="dropdown-item text-danger" href="#"
									onclick="event.preventDefault();
												document.getElementById('logout-form').submit();">
										{{ __('Déconnexion') }}
									</a>
								</li>
							</ul>
						</div>
					</div><!--//app-utilities-->
				</div><!--//row-->
			</div><!--//app-header-content-->
		</div><!--//container-fluid-->
	</div><!--//app-header-inner-->

	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
		@csrf
	</form>
</body>
</html>
