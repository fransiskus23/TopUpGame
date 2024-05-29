@if (isset($contentDetails['statistics']))
    <!-- COUNTER SECTION -->
    <section class="counter-section">
        <div class="container">
            <div class="row">
                @foreach ($contentDetails['statistics'] as $item)
                    <div class="col-md-6 col-lg-3">
                        <div
                            class="counter-box"
                            data-aos-duration="800"
                            data-aos="fade-up"
                            data-aos-anchor-placement="center-bottom"
                        >
                            <h2><span class="counter">@lang(@$item->description->number)</span><sup>+</sup></h2>
                            <h5>@lang(@$item->description->title)</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
