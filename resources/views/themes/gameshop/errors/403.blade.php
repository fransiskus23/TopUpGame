@extends($theme . 'layouts.app')
@section('title', '403 Forbidden')


@section('content')
    <section  class="login-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-12 text-center">
                    <span class="display-1 d-block ">{{ trans('Forbidden') }}</span>
                    <div class="mb-4 lead mt-3">
                        {{ trans("You don't have permission to access ‘/’ on this server") }}</div>
                    <a class="btn-base" href="{{ url('/') }}">@lang('Back To Home')</a>
                </div>
            </div>
        </div>
    </section>
    <!-- /ERROR -->
@endsection
