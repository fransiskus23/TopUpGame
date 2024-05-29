@extends($theme . 'layouts.app')
@section('title', trans('About Us'))

@section('content')

    @include($theme . 'sections.about')
    @include($theme . 'sections.why-choose-us')
    @include($theme . 'sections.counter')
    @include($theme . 'sections.testimonial')

@endsection
