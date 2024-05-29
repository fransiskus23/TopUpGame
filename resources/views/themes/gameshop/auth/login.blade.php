@extends($theme . 'layouts.app')
@section('title')
    @lang('Login')
@endsection


@section('content')

    <!-- LOGIN SECTION -->
    <section class="login-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 pe-md-5">
                    <div class="img-box text-center">
                        <img class="img-fluid" src="{{ getFile(config('location.logo.path') . 'game-character5.png') }}"
                            alt="..." />
                    </div>
                </div>
                <div class="col-md-6">
                    <h2>@lang('sign in')</h2>
                    <p>
                        @lang('Please sign-in to your account and start the adventure...')
                    </p>
                    <div class="contact-box">
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label">@lang('Your Email or Username')
                                </label>
                                <input type="text" name="username" class="form-control" id="exampleFormControlInput1"
                                    placeholder="@lang('name@example.com')" />
                                @error('username')
                                    <span class="text-danger  mt-1">{{ $message }}</span>
                                @enderror
                                @error('email')
                                    <span class="text-danger  mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label">@lang('Your password')
                                </label>
                                <input type="password" name="password" class="form-control" id="exampleFormControlInput1"
                                    placeholder="********" />
                                @error('password')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                @enderror
                                <div class="link" align="right">
                                    <a href="{{ route('password.request') }}">@lang('Forgot Password')</a>
                                </div>
                            </div>

                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" name="remember" {{ old('remember') ? 'checked' : '' }}
                                        type="checkbox" value="" id="remember" />
                                    <label class="form-check-label" for="remember">
                                        @lang('Remember me')
                                    </label>
                                </div>
                                <div class="link">
                                    @lang("Don't have any account?") <a href="{{ route('register') }}">@lang('Register')</a>
                                </div>
                            </div>

                            @if(basicControl()->reCaptcha_status_login)
                                <div class="box mb-4 form-group">
                                    {!! NoCaptcha::renderJs(session()->get('trans')) !!}
                                    {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
                                    @error('g-recaptcha-response')
                                    <span class="text-danger mt-1">@lang($message)</span>
                                    @enderror
                                </div>
                            @endif

                            <button class="game-btn" type="submit">
                                @lang('sign in')
                                <img src="{{ asset($themeTrue.'/images/icon/arrow-white.png') }}" alt="..." />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
