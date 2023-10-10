<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href='{{ asset('img/logo_sfondo_white.png') }}'>

    <title>BoolBnB</title>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <style>
        body {
            display: none
        }
    </style>


    {{-- Style --}}
    @yield('styles')

    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])
    @vite(['resources/scss/header.scss'])
    @vite(['resources/scss/footer.scss'])
</head>

<body>
    <div id="app">
        <header class="d-flex align-items-center my-navbar">
            <div class="container-xxl d-flex justify-content-between align-items-center">
                <div class="img-container d-flex align-items-center">
                    <a href="http://localhost:5174/"><img class="img-fluid h-100"
                            src="{{ Vite::asset('public/img/logo_navbar.png') }}" alt=""></a>
                    <p class="ps-3 pt-3"><a href="http://localhost:5174/"
                            class="link-secondary link-offset-2 link-underline link-underline-opacity-0 link-opacity-50-hover"><strong>Home</strong></a>
                    </p>
                    @if (Auth::user())
                        <p class="ps-3 pt-3"><a href="{{ route('user.houses.index') }}"
                                class="link-secondary link-offset-2 link-underline link-underline-opacity-0 link-opacity-50-hover"><strong>{{ __('Le tue case') }}</strong></a>
                        </p>
                    @endif
                </div>
                <div class="pe-3 action-button-group ">
                    @guest
                        <div class="log-button">
                            <a class="btn-custom" href="{{ route('login') }}">Accedi</a>
                            <a class="btn-custom" href="{{ route('register') }}">Registrati</a>
                        </div>
                    @endguest
                    @auth
                        <div class="dropdown d-none d-md-block  ">
                            <button class="user btn dropdown-toggle user-button" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span>{{ Auth::user()->name ? Auth::user()->name : Auth::user()->email }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                {{-- <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('dashboard') }}">{{ __('Dashboard') }}</a>
                                </li> --}}
                                <li class="nav-item">
                                    <a class="dropdown-item"
                                        href="{{ route('user.houses.trash') }}">{{ __('Cestino') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('profile') }}">{{ __('Profile') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="'http://localhost:5174/"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                    <!-- icona per il login o sing in   -->
                    <div class="user-icon dropdown ">
                        <button class="user btn dropdown-toggle no-caret" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class=" fa-solid fa-circle-user"></i>
                        </button>
                        <ul class="dropdown-menu">
                            @guest
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('login') }}">{{ __('Accedi') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="dropdown-item" href="{{ route('register') }}">{{ __('Registrati') }}</a>
                                    </li>
                                @endif
                                <!-- bottone per il login e sing in -->
                            @else
                                {{-- <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('dashboard') }}">{{ __('Dashboard') }}</a>
                                </li> --}}
                                <li class="nav-item">
                                    <a class="dropdown-item"
                                        href="{{ route('user.houses.trash') }}">{{ __('Cestino') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('profile') }}">{{ __('Profile') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('http://localhost:5174/') }}"
                                        onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            @endguest
                        </ul>
                    </div>

                </div>
            </div>
        </header>
        <main class="">
            <div class="container">
                {{-- Alerts --}}
                @include('includes.alert')
            </div>
            {{-- Content --}}
            @yield('content')
        </main>
    </div>
    @include('layouts.footer')
    @yield('scripts')
</body>

</html>
