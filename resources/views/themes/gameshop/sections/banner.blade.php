@if(isset($contentDetails['slider']))
    <!-- BANNER SECTION -->
    <section id="banner" class="banner">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="skitter skitter-large with-dots">
                        <ul>
                            @foreach ($contentDetails['slider'] as $item)
                                <li>
                                    <a href="javascript:void(0)">
                                        <img
                                            src="{{getFile(config('location.content.path').@$item->content->contentMedia->description->image)}}"
                                            class="cut"/>
                                    </a>
                                    <div class="label_text">
                                        <div class="text-box">
                                            <h5>@lang(@$item->description->sub_title)</h5>
                                            <h1>
                                                @lang(@$item->description->title)
                                            </h1>
                                            <a href="{{@$item->content->contentMedia->description->button_link}}">
                                                <button class="game-btn">
                                                    @lang(@$item->description->button_name)
                                                    <img
                                                        src="{{asset($themeTrue.'/images/icon/arrow-white.png')}}"
                                                        alt="..."
                                                    /></button
                                                >
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endif
