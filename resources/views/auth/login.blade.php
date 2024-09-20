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
                    <h2 class="auth-heading mb-5">Authentification</h2>
                    <div class="auth-form-container text-start">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form class="auth-form login-form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="email mb-3">
                                <label class="form-label" for="signin-email">Email</label>
                                <input id="signin-email" name="email" type="email" class="form-control" placeholder="Adresse email" required value="{{ old('email') }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="password mb-3">
                                <label class="form-label" for="signin-password">Mot de passe</label>
                                <input id="signin-password" name="password" type="password" class="form-control" placeholder="Mot de passe" required>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="extra mt-3 row justify-content-between">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="RememberPassword">
                                            <label class="form-check-label" for="RememberPassword">
                                                Souviens-toi de moi
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="forgot-password text-end">
                                            @if (Route::has('password.request'))
                                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                                    {{ __('Mot de passe oublié ?') }}
                                                </a>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-app-primary w-100">Se Connecter</button>
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
