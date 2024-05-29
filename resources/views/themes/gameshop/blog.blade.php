@extends($theme . 'layouts.app')
@section('title', trans($title))

@section('content')
    <!-- BLOG SECTION -->
    @include($theme . 'sections.blog')

@endsection
