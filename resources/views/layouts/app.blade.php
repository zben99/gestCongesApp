<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Première Agence de Microfinance - Admin @yield('title')</title>

  @include("layouts.admin.css")
  @yield('css')

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{asset('images/logo.png')}}" alt="AdminLTELogo" height="60" width="60">
  </div>


  @include("layouts.admin.header")


  @include("layouts.admin.sidebar")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    @yield('contenu')


  </div>



</div>
<!-- ./wrapper -->

@include("layouts.admin.footer")

@include("layouts.admin.script")

@yield('script')
</body>
</html>
