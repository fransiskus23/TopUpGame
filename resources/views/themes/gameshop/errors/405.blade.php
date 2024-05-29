@extends($theme . 'layouts.app')
@section('title', '405')


@section('content')
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-12 text-center">
                    <span class="display-1 d-block  ">{{ trans('405') }}</span>
                    <div class="mb-4 lead  mt-3 ">{{ trans('Method Not Allowed') }}</div>
                    <a class="btn-base" href="{{ url('/') }}">@lang('Back To Home')</a>
                </div>
            </div>
        </div>
    </section>
@endsection
