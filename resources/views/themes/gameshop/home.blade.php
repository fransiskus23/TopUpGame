@extends($theme . 'layouts.app')
@section('title', trans('Home'))

@section('content')

    @include($theme . 'sections.banner')

    @include($theme . 'sections.search')

    @if(config('basic.top_up'))
        @include($theme . 'sections.top-up')
    @endif
    @include($theme . 'sections.about')

    @if(config('basic.voucher'))
        @include($theme . 'sections.voucher')
    @endif

    @include($theme . 'sections.why-choose-us')

    @if(config('basic.gift_card'))
        @include($theme . 'sections.gift-card')
    @endif

    @include($theme . 'sections.faq')

    @if(config('basic.sell_post'))
        @include($theme . 'sections.sell-post')
    @endif

    @include($theme . 'sections.counter')
    @include($theme . 'sections.testimonial')
    @include($theme . 'sections.blog')

@endsection
