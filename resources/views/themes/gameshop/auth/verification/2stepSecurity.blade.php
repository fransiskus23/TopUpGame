@extends($theme . 'layouts.app')
@section('title', $page_title)

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
                    <h2>@lang('2FA CODE')</h2>
                    <form action="{{ route('user.twoFA-Verify') }}" method="post">
                        @csrf
                        <div class="contact-box">
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label">@lang('Code')
                                </label>
                                <input type="text" name="code" value="{{ old('code') }}" class="form-control"
                                    id="exampleFormControlInput1" placeholder="@lang('Code')" />
                                @error('code')
                                    <span class="text-danger  mt-1">{{ $message }}</span>
                                @enderror
                                @error('error')
                                    <span class="text-danger  mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <button class="game-btn" type="submit">
                            @lang('Submit')
                            <img src="{{ asset($themeTrue . '/images/icon/arrow-white.png') }}" alt="..." />
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
