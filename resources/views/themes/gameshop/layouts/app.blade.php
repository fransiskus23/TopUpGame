<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


    @include('partials.seo')


    <link rel="stylesheet" type="text/css" href="{{ asset($themeTrue . 'css/bootstrap.min.css') }}"/>
    @stack('css-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset($themeTrue . 'css/animate.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset($themeTrue . 'css/owl.carousel.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset($themeTrue . 'css/owl.theme.default.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset($themeTrue . 'css/skitter.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset($themeTrue . 'css/aos.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset($themeTrue . 'css/ion.range-slider.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset($themeTrue . 'css/style.css') }}"/>

    @stack('style')

</head>

<body>
<!-- prelaoder -->
<div id="preloader" class="preloader">
    <div id="loader" class="wrapper-triangle">
        <div class="pen">
            <div class="line-triangle">
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
            </div>
            <div class="line-triangle">
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
            </div>
            <div class="line-triangle">
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
            </div>
        </div>
    </div>
</div>


<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ getFile(config('location.logoIcon.path') . 'logo.png') }}" alt="..."/>
        </a>
        @auth
            <span class="navbar-text">
                    <!-- notification panel -->
                @include($theme.'partials.pushNotify')


            <!-- user panel -->
                    <div class="user-panel">
                        <button class="user-icon">
                            <img src="{{ asset($themeTrue . '/images/icon/user2.png') }}" alt="..."/>
                        </button>
                        <div class="user-drop-dropdown">
                            <ul>
                                <li>
                                    <a href="{{ route('user.home') }}"><img class="me-2"
                                                                            src="{{ asset($themeTrue . '/images/icon/block.png') }}"
                                                                            alt="..."/>
                                        @lang('Dashboard')</a>
                                </li>
                                <li>
                                    <a href="{{ route('user.profile') }}"><img class="me-2"
                                                                               src="{{ asset($themeTrue . '/images/icon/editing.png') }}"
                                                                               alt="..."/>
                                        @lang('My Profile')</a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img
                                            class="me-2" src="{{ asset($themeTrue . '/images/icon/logout.png') }}"
                                            alt="..."/>
                                        @lang('Sign out')</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </span>

        @endauth
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <img src="{{ asset($themeTrue . '/images/icon/menu.png') }}" alt="..."/>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{menuActive('home')}}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{menuActive('about')}}" href="{{ route('about') }}">@lang('About Us')</a>
                </li>
                @if (config('basic.top_up'))
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('shop') && request()->sortByCategory == 'topUp') active @endif"
                           href="{{ route('shop') . '?sortByCategory=topUp' }}">@lang('Top Up')</a>
                    </li>
                @endif

                @if (config('basic.voucher'))
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('shop') && request()->sortByCategory == 'voucher') active @endif"
                           href="{{ route('shop') . '?sortByCategory=voucher' }}">@lang('Voucher') </a>
                    </li>
                @endif

                @if (config('basic.gift_card'))
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('shop') && request()->sortByCategory == 'giftCard') active @endif"
                           href="{{ route('shop') . '?sortByCategory=giftCard' }}">@lang('Gift Card')</a>
                    </li>
                @endif

                @if (config('basic.sell_post'))
                    <li class="nav-item">
                        <a class="nav-link {{menuActive('buy')}}" href="{{route('buy')}}">@lang('Buy ID')</a>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link {{menuActive('contact')}}" href="{{ route('contact') }}">@lang('Contact')</a>
                </li>

                @guest

                    <li class="nav-item">
                        <a class="nav-link {{menuActive('login')}}" href="{{ route('login') }}">@lang('Sign In')</a>
                    </li>
                    @if(config('basic.registration'))
                        <li class="nav-item">
                            <a class="nav-link {{menuActive('register')}}"
                               href="{{ route('register') }}">@lang('Sign Up')</a>
                        </li>
                    @endif
                @endguest


            </ul>
        </div>
    </div>
</nav>

@include($theme . 'partials.banner')
@yield('content')

@include($theme . 'partials.footer')


<script src="{{ asset($themeTrue . 'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/jquery-3.6.0.min.js') }}"></script>
@stack('extra-js')

<script src="{{ asset($themeTrue . 'js/fontawesome.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/owl.carousel.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/jquery.easing.1.3.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/jquery.skitter.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/aos.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/ion.range-slider.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/script.js') }}"></script>


<script src="{{ asset('assets/global/js/notiflix-aio-2.7.0.min.js') }}"></script>

@include('plugins')

@include($theme . 'partials.notification')

<script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
<script src="{{ asset('assets/global/js/vue.min.js') }}"></script>
<script src="{{ asset('assets/global/js/axios.min.js') }}"></script>

@stack('script')


</body>

</html>
