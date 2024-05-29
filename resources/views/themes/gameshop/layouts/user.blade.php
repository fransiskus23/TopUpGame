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
                  @include($theme.'partials.pushNotify')

                <!-- user panel -->
                    <div class="user-panel">
                        <button class="user-icon">
                            <img src="{{ asset($themeTrue.'/images/icon/user2.png') }}" alt="..."/>
                        </button>
                        <div class="user-drop-dropdown">
                            <ul>
                                <li>
                                    <a href="{{route('user.profile')}}"><img class="me-2"
                                                                             src="{{ asset($themeTrue.'/images/icon/editing.png') }}"
                                                                             alt="..."/>
                                        @lang('My Profile')</a>
                                </li>

                                 <li>
                                    <a href="{{route('user.twostep.security')}}"><img class="me-2"
                                                                                      src="{{ asset($themeTrue.'/images/icon/block.png') }}"
                                                                                      alt="..."/>
                                        @lang('2FA Security')</a>
                                </li>
                                <li>
                                    <a href="{{route('user.ticket.list')}}"><img class="me-2"
                                                                                 src="{{ asset($themeTrue.'/images/icon/editing.png') }}"
                                                                                 alt="..."/>
                                        @lang('Support Ticket')</a>
                                </li>


                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img
                                            class="me-2"
                                            src="{{ asset($themeTrue.'/images/icon/logout.png') }}" alt="..."/>
                                        @lang('Sign out')</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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
            <img src="{{ asset($themeTrue.'/images/icon/menu.png') }}" alt="..."/>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{menuActive('home')}}" aria-current="page" href="{{ route('home') }}">@lang('Home')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{menuActive('user.home')}}" href="{{route('user.home')}}">@lang('Dashboard')</a>
                </li>


                @if(config('basic.sell_post'))
                    <li class="nav-item dropdown">
                        <a class="nav-link {{menuActive('user.sellCreate')}} {{menuActive('user.sellList')}} {{menuActive('user.sellPostOfferMore')}} dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            @lang('My Sales')

                            <i class="fas fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item {{menuActive('user.sellCreate')}}" href="{{route('user.sellCreate')}}">@lang('Create Post')</a>
                            </li>
                            <li>
                                <a class="dropdown-item {{menuActive('user.sellList')}}" href="{{route('user.sellList')}}">@lang('Sales List')</a>
                            </li>
                            <li>
                                <a class="dropdown-item {{menuActive('user.sellPostOfferMore')}}"
                                   href="{{route('user.sellPostOfferMore')}}">@lang('Offer List')</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(config('basic.top_up') || config('basic.voucher') || config('basic.gift_card') || config('basic.sell_post'))
                    <li class="nav-item dropdown">
                        <a class="nav-link {{menuActive('user.topUpOrder')}} {{menuActive('user.voucherOrder')}} {{menuActive('user.giftCardOrder')}} {{menuActive('user.sellPostOrder')}}
                        {{menuActive('user.sellPostMyOffer')}} dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            @lang('My Orders')
                            <i class="fas fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @if(config('basic.top_up'))
                                <li>
                                    <a class="dropdown-item {{menuActive('user.topUpOrder')}}" href="{{route('user.topUpOrder')}}">@lang('Top Up')</a>
                                </li>
                            @endif

                            @if(config('basic.voucher'))
                                <li>
                                    <a class="dropdown-item {{menuActive('user.voucherOrder')}}" href="{{route('user.voucherOrder')}}">@lang('Voucher')</a>
                                </li>
                            @endif
                            @if(config('basic.gift_card'))
                                <li>
                                    <a class="dropdown-item {{menuActive('user.giftCardOrder')}}"
                                       href="{{route('user.giftCardOrder')}}">@lang('Gift Card')</a>
                                </li>
                            @endif
                            @if(config('basic.sell_post'))
                                <li>
                                    <a class="dropdown-item {{menuActive('user.sellPostOrder')}}"
                                       href="{{route('user.sellPostOrder')}}">@lang('ID Purchase')</a>
                                </li>
                            @endif
                            @if(config('basic.sell_post'))
                                <li>
                                    <a class="dropdown-item {{menuActive('user.sellPostMyOffer')}}"
                                       href="{{route('user.sellPostMyOffer')}}">@lang('My Offer')</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{menuActive('user.payout.money')}}" href="{{route('user.payout.money')}}">@lang('Payout')</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link {{menuActive('user.transaction')}} {{menuActive('user.fund-history')}} {{menuActive('user.payout.history')}} dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        @lang('History')

                        <i class="fas fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item {{menuActive('user.transaction')}}" href="{{route('user.transaction')}}">@lang('Transaction')</a>
                        </li>
                        <li>
                            <a class="dropdown-item {{menuActive('user.fund-history')}}" href="{{route('user.fund-history')}}">@lang('Payment Log')</a>
                        </li>

                        <li>
                            <a class="dropdown-item {{menuActive('user.payout.history')}}" href="{{route('user.payout.history')}}">@lang('Payout Log')</a>
                        </li>

                    </ul>
                </li>


            </ul>
        </div>
    </div>
</nav>

@include($theme . 'partials.banner')
@yield('content')

@include($theme . 'partials.footer')

@stack('loadModal')
@stack('extra-content')

<script src="{{ asset($themeTrue . 'js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/bootstrap.bundle.min.js') }}"></script>
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
