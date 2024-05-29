@if(isset($templates['faq'][0]) && $faq = $templates['faq'][0])
    <!-- FAQ SECTION -->
    <section class="faq-section">
        <div class="container">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <div
                        class="img-box pe-md-5"
                        data-aos-duration="800"
                        data-aos="fade-right"
                        data-aos-anchor-placement="center-bottom">
                        <img
                            src="{{getFile(config('location.content.path').@$faq->templateMedia()->image)}}"
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
                        data-aos-anchor-placement="center-bottom">
                        <h2>@lang($faq->description->title)</h2>

                        @if(isset($contentDetails['faq']))
                            <div class="accordion" id="accordionExample">
                                @forelse ( $contentDetails['faq'] as $key => $item )
                                    <div class="accordion-item">
                                        <h5 class="accordion-header" id="heading{{$key}}">
                                            <button
                                                class="accordion-button"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{$key}}"
                                                aria-expanded="false"
                                                aria-controls="collapse{{$key}}"
                                            >
                                                @lang(@$item->description->title)
                                            </button>
                                        </h5>
                                        <div
                                            id="collapse{{$key}}"
                                            class="accordion-collapse collapse @if ($key==0)
                                                show"
                                            @endif
                                            aria-labelledby="heading{{$key}}"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <p>
                                                    @lang(@$item->description->description)
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty

                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
