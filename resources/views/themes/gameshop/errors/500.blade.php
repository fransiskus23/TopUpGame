@extends($theme . 'layouts.app')
@section('title', '500')


@section('content')
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-12 text-center">
                    <span class="display-1 d-block">@lang('Internal Server Error')</span>
                    <div class="mb-4 lead mt-3 ">@lang('The server encountered an internal error misconfiguration and was unable to complate your request. Please contact the server administrator.')</div>
                    <a class="btn-base" href="{{ url('/') }}">@lang('Back To Home')</a>
                </div>
            </div>
        </div>
    </section>
@endsection
