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
                    <p>
                        {{-- @lang('Please sign-in to your account and start the adventure...') --}}
                    </p>

                    <form action="{{ route('password.update') }}" method="post">
                        @csrf
                        <div class="contact-box">

                            @error('token')
                                <div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
                                    {{ trans($message) }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">text-center</span>
                                    </button>
                                </div>
                            @enderror

                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label">@lang('New Password')
                                </label>
                                <input type="password" name="password" class="form-control" id="exampleFormControlInput1"
                                    placeholder="@lang('**********')" />
                                @error('password')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label">@lang('Confirm Password')
                                </label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    id="exampleFormControlInput1" placeholder="@lang('**********')" />
                            </div>
                        </div>
                        <button class="game-btn" type="submit">
                            @lang('Change Password')
                            <img src="{{ asset($themeTrue.'/images/icon/arrow-white.png') }}" alt="..." />
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
