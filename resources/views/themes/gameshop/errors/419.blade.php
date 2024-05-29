@extends($theme . 'layouts.app')
@section('title', '419')


@section('content')
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-12 text-center">
                    <span class="display-1 d-block ">@lang('419')</span>
                    <div class="mb-4 lead mt-3 ">@lang('Sorry, your session has expired')</div>
                    <a class="btn-base" href="{{ url('/') }}">@lang('Back To Home')</a>
                </div>
            </div>
        </div>
    </section>
@endsection
