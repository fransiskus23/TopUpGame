
@if(isset($templates['why-choose-us'][0]) && $whyChooseUs = $templates['why-choose-us'][0])
 <section class="choose-section">
    <div class="container">


        <div class="row align-items-center">
            <div class="col-md-6">
                <div
                    class="text-box"
                    data-aos-duration="800"
                    data-aos="fade-right"
                    data-aos-anchor-placement="center-bottom">
                    <h2>@lang(optional($whyChooseUs->description)->title)</h2>

                @if(isset($contentDetails['why-choose-us']))
                    @foreach ($contentDetails['why-choose-us'] as $item)
                    <div class="choose-box">
                        <img src="{{getFile(config('location.content.path').@$item->content->contentMedia->description->image)}}" alt="..."/>
                        <div class="text">
                            <h5> @lang(optional($item->description)->title)</h5>
                            <span>@lang(optional($item->description)->information)</span>
                        </div>
                    </div>
                    @endforeach
                @endif

                    <a href="{{optional($whyChooseUs->templateMedia())->button_link}}"><button class="game-btn">
                            @lang(optional($whyChooseUs->description)->button_name)
                            <img src="{{asset($themeTrue).'/images/icon/arrow-white.png'}}" alt="..."/>
                        </button></a>
                </div>
            </div>

            @if(isset($templates['why-choose-us'][0]) && $whyChooseUs = $templates['why-choose-us'][0])
            <div class="col-md-6">
                <div
                    class="img-box ps-md-5"
                    data-aos-duration="800"
                    data-aos="fade-left"
                    data-aos-anchor-placement="center-bottom"
                >
                    <img
                        src="{{getFile(config('location.content.path').@optional($whyChooseUs->templateMedia())->image)}}"
                        alt="..."
                        class="img-fluid"
                    />
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif
