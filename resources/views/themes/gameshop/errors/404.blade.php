@extends($theme . 'layouts.app')
@section('title', '404')


@section('content')
    <section  class="login-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-12 text-center">
                    <span class="display-1 d-block ">{{ trans('Opps!') }}</span>
                    <div class="mb-4 lead mt-3 ">{{ trans('The page you are looking for was not found.') }}
                    </div>
                    <a class="btn  btn-base" href="{{ url('/') }}">@lang('Back To Home')</a>
                </div>
            </div>
        </div>
    </section>
@endsection
