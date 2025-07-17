<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <link rel="stylesheet" href="/css/master.css">

    <link rel="icon" href="{{ asset('images/' . ($app_settings->favicon ?? 'favicon.ico')) }}">
    <title>{{ $app_settings->app_name ?? 'Stocky | Ultimate Inventory With POS' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body class="bg-light">
    <noscript>
        <strong>
            We're sorry but Stocky doesn't work properly without JavaScript
            enabled. Please enable it to continue.</strong>
    </noscript>

    <!-- built files will be auto injected -->
    <div class="loading_wrap" id="loading_wrap">
        <div class="loader_logo">
            <img src="{{ asset('images/' . ($app_settings->logo ?? 'logo.png')) }}" class="" alt="logo" />

        </div>

        <div class="loading"></div>
    </div>
      <div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
    <div class="text-center mb-4">
      <h1 class="display-4">Welcome to Tenant System</h1>
      <p class="lead">Manage your rental property and tenants efficiently.</p>
    </div>

    <div class="d-flex gap-3">
      <a href="/register" class="btn btn-primary btn-lg">Register</a>
      <a href="/login" class="btn btn-outline-secondary btn-lg">Login</a>
    </div>
  </div>


    <script src="/js/login.min.js?v=5.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
