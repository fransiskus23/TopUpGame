@extends($theme . 'layouts.app')
@section('title')
    @lang('Reset Password')
@endsection


@section('content')

    <section class="login-section">
        <div class="container">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                    {{ trans(session('status')) }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">text-center</span>
                    </button>
                </div>
            @endif

            <div class="row align-items-center">
                <div class="col-md-6 pe-md-5">
                    <div class="img-box text-center">
                        <img class="img-fluid" src="{{ getFile(config('location.logo.path') . 'game-character5.png') }}"
                            alt="..." />
                    </div>
                </div>
                <div class="col-md-6">
                    <h2>@lang('Reset Password')</h2>
                    <form action="{{ route('password.email') }}" method="post">
                        @csrf
                        <div class="contact-box">
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label">@lang('Your Email Address')
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                    id="exampleFormControlInput1" placeholder="@lang('Enter your Email Address')" />
                                @error('email')
                                    <span class="text-danger  mt-1">{{ trans($message) }}</span>
                                @enderror
                            </div>
                        </div>
                        <button class="game-btn" type="submit">
                            @lang('send password reset link')
                            <img src="{{ asset($themeTrue.'/images/icon/arrow-white.png') }}" alt="..." />
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
