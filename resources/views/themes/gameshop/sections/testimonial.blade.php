@if (isset($contentDetails['whats-clients-say']))
    <!-- TESTIMONIAL SECTION -->
    <section class="testimonial-section">
        <div class="container">
            @if (isset($templates['whats-clients-say'][0]) && ($whatClientsSay = $templates['whats-clients-say'][0]))
                <div class="row">
                    <div class="col">
                        <div class="header-text">
                            <h2>@lang($whatClientsSay->description->title)</h2>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col">
                    <div class="owl-carousel testimonials owl-loaded owl-drag">
                        @foreach ($contentDetails['whats-clients-say'] as $item)
                            <div class="review-box" data-aos-duration="800" data-aos="fade-up"
                                 data-aos-anchor-placement="center-bottom">
                                <div class="img-box">
                                    <img
                                        src="{{ getFile(config('location.content.path') . @$item->content->contentMedia->description->image) }}"
                                        alt="..." class="img-fluid"/>
                                </div>
                                <div class="text-box">
                                    <p class="description">
                                        @lang(@$item->description->description)
                                    </p>
                                    <h5 class="name">@lang(@$item->description->name)</h5>
                                    <span class="title">@lang(@$item->description->designation)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>
@endif
