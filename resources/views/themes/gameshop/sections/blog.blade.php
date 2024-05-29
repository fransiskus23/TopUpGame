@if (isset($contentDetails['blog']))
    <!-- BLOG SECTION -->
    <section class="blog-section">
        <div class="container">

            @if(!request()->routeIs('blog'))
                @if (isset($templates['blog'][0]) && ($ourLatestPost = $templates['blog'][0]))
                    <div class="row">
                        <div class="col">
                            <div class="header-text-link">
                                <h2>@lang($ourLatestPost->description->title)</h2>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <div class="row">
                @foreach ($contentDetails['blog'] as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-box" data-aos-duration="800" data-aos="fade-right"
                             data-aos-anchor-placement="center-bottom">
                            <div class="img-box">
                                <img
                                    src="{{ getFile(config('location.content.path') . @$item->content->contentMedia->description->image) }}"
                                    alt="..." class="img-fluid"/>
                                <span class="author">@lang('Admin')</span>
                            </div>
                            <div class="text-box">
                                <h5 class="title">
                                    <a href="{{ route('blogDetails', [slug(@$item->description->title), $item->content_id]) }}">
                                        @lang(@$item->description->title)</a>
                                </h5>
                                <span class="date"> @lang(@$item->description->date_time)</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endif
