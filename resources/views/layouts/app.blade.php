<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Santoku') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg sticky-top navbar-light bg-white nav-border navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-cloud-upload-alt"></i> {{ config('app.name', 'Santoku') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li id="provision-navi" class="nav-item">
                        <a href="{{ route('index') }}" class="nav-link">
                            <i class="fas fa-server"></i> Provision
                        </a>
                    </li>
                    <li id="build-navi" class="nav-item">
                        <a href="{{ route('build') }}" class="nav-link">
                            <i class="fas fa-hammer"></i> Build
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
</div>

<footer>
    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}"></script>
    @yield('scripts')
</footer>
</body>
</html>
