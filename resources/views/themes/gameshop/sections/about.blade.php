@if(isset($templates['about-us'][0]) && $aboutUs = $templates['about-us'][0])
    <!-- ABOUT SECTION -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-md-6">
                    <div
                        class="img-box pe-md-5"
                        data-aos-duration="800"
                        data-aos="fade-right"
                        data-aos-anchor-placement="center-bottom"
                    >
                        <img
                            src="{{getFile(config('location.content.path').@$aboutUs->templateMedia()->image)}}"
                            alt="..."
                            class="img-fluid"
                        />
                    </div>
                </div>

                <div class="col-md-6">
                    <div
                        class="text-box"
                        data-aos-duration="800"
                        data-aos="fade-left"
                        data-aos-anchor-placement="center-bottom"
                    >
                        @if(isset($templates['about-us'][0]) && $aboutUs = $templates['about-us'][0])
                            <h2>@lang($aboutUs->description->title)</h2>
                            <p>
                                @lang($aboutUs->description->short_description)
                            </p>
                        @endif

                        @if(isset($templates['about-us'][0]) && $aboutUs = $templates['about-us'][0])

                            <a href="{{(@$aboutUs->templateMedia()->button_link)}}">
                                <button class="game-btn">
                                    @lang($aboutUs->description->button_name)
                                    <img src="{{asset($themeTrue.'/images/icon/arrow-white.png')}}" alt="..."/>
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endif
