





<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page d'authentification">
    <meta name="author" content="ONEA">
    <link rel="shortcut icon" href="favicon.ico">

    <!-- FontAwesome JS -->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
	<link id="theme-style" rel="stylesheet" href="assets/css/portal1.css">

    <title>Interface Authentification</title>
</head>

<body class="app app-login p-0">
    <div class="row g-0 app-auth-wrapper">
        <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
            <div class="d-flex flex-column align-content-end">
                <div class="app-auth-body mx-auto">
                    <div class="app-auth-branding mb-4">
                        <a class="app-logo" href="/">
                            <img class="logo-icon me-2" src="{{asset('images/logo-ONEA.jpg')}}" alt="logo">
                        </a>
                    </div>
                    <h2 class="auth-heading mb-5">Mot de passe oublié</h2>
                    <div class="auth-form-container text-start">
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form class="auth-form login-form" method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="email mb-3">
                                <label class="form-label" for="signin-email">Email</label>
                                <input id="signin-email" name="email" type="email" class="form-control" placeholder="Adresse email" required value="{{ old('email') }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-app-primary w-100">Envoyer le lien de réinitialisation du mot de passe</button>
                            </div>
                        </form>
                    </div>
                </div>
                <footer class="app-auth-footer mt-4">
                    <div class="container text-center py-3">
                        <small class="copyright">Conçu par <a class="app-link" href="#">Mentley & Partners</a></small>
                    </div>
                </footer>
            </div>
        </div>
        <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
            <div class="auth-background-holder"></div>
            <div class="auth-background-mask"></div>
        </div>
    </div>
</body>
</html>
