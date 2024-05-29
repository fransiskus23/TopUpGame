@extends($theme . 'layouts.app')
@section('title')
    @lang('Register')
@endsection

@section('content')


    <!-- REGISTER SECTION -->
    <section class="login-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 pe-md-5">
                    <div class="img-box text-center">
                        <img class="img-fluid" src="{{ getFile(config('location.logo.path') . 'game-character5.png') }}"
                            alt="..." />
                    </div>
                </div>
                <div class="col-md-7">
                    <h2>@lang('sign up')</h2>
                    <div class="contact-box">
                        <form action="{{ route('register') }}" method="post">
                            @csrf

                            <div class="row">
                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label">@lang('First name')
                                    </label>
                                    <input type="text" name="firstname" value="{{ old('firstname') }}"
                                        class="form-control" id="exampleFormControlInput1"
                                        placeholder="@lang('John')" /> @error('firstname')
                                        <span class="text-danger  mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label">@lang('Last name')
                                    </label>
                                    <input type="text" name="lastname" value="{{ old('lastname') }}"
                                        class="form-control" id="exampleFormControlInput1"
                                        placeholder="@lang('Doe')" /> @error('lastname')
                                        <span class="text-danger  mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">

                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label">@lang('Username')
                                    </label>
                                    <input type="text" name="username" value="{{ old('username') }}"
                                        class="form-control" id="exampleFormControlInput1"
                                        placeholder="@lang('username')" /> @error('username')
                                        <span class="text-danger  mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label">@lang('Your Email')
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                        id="exampleFormControlInput1" placeholder="@lang('name@example.com')" />
                                    @error('email')
                                        <span class="text-danger  mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">

                                <div class="mb-4">
                                    @php
                                        $country_code = (string) @getIpInfo()['code'] ?: null;
                                        $myCollection = collect(config('country'))->map(function ($row) {
                                            return collect($row);
                                        });
                                        $countries = $myCollection->sortBy('code');
                                    @endphp


                                    <div class="form-group form-box">
                                        <label for="exampleFormControlInput1" class="form-label">@lang('Your Phone Number')
                                        </label>
                                        <div class="input-group prepend">
                                            <button class="game-btn prepend-btn w-50" type="button" >
                                                <select name="phone_code" class="form-control form-select country_code dialCode-change">
                                                    @foreach (config('country') as $value)
                                                        <option value="{{ $value['phone_code'] }}" class="dropdown-item"
                                                                data-name="{{ $value['name'] }}"
                                                                data-code="{{ $value['code'] }}"
                                                            {{ $country_code == $value['code'] ? 'selected' : '' }}>
                                                            {{ $value['name'] }} ({{ $value['phone_code'] }})

                                                        </option>
                                                    @endforeach
                                                </select>
                                            </button>


                                            <input type="text" name="phone" class="form-control dialcode-set"
                                                   value="{{ old('phone') }}" placeholder="@lang('Your Phone Number')">


                                        </div>

                                        @error('phone')
                                        <span class="text-danger mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <input type="hidden" name="country_code" value="{{ old('country_code') }}"
                                        class="text-dark">
                                </div>
                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label">@lang('Your password')
                                    </label>
                                    <input type="password" name="password" class="form-control"
                                        id="exampleFormControlInput1" placeholder="********" /> @error('password')
                                        <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label">@lang('Confirm password')
                                    </label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        id="exampleFormControlInput1" placeholder="********" />
                                </div>
                            </div>

                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <div class="link">
                                    @lang('Already an user?') <a href="{{ route('login') }}">@lang('Login')</a>
                                </div>
                            </div>

                            @if(basicControl()->reCaptcha_status_registration)
                                <div class="col-md-6 box mb-4 form-group">
                                    {!! NoCaptcha::renderJs(session()->get('trans')) !!}
                                    {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
                                    @error('g-recaptcha-response')
                                    <span class="text-danger mt-1">@lang($message)</span>
                                    @enderror
                                </div>
                            @endif

                            <button class="game-btn">
                                @lang('Sign Up')
                                <img src="{{ asset($themeTrue.'/images/icon/arrow-white.png') }}" alt="..." />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
         .form-box .input-group.prepend .form-select {
            -webkit-clip-path: polygon(3% 0, 100% 0, 100% 0, 100% 70%, 100% 100%, 0 100%, 0 100%, 0% 30%);
            clip-path: polygon(3% 0, 100% 0, 100% 0, 100% 70%, 100% 100%, 0 100%, 0 100%, 0% 30%);
        }
    </style>
@endpush
@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
            setDialCode();
            $(document).on('change', '.dialCode-change', function() {
                setDialCode();
            });

            function setDialCode() {
                let currency = $('.dialCode-change').val();
                $('.dialcode-set').val(currency);
            }

        });
    </script>
@endpush
