@extends($theme . 'layouts.app')
@section('title', trans('Blog Details'))

@section('content')

    <!-- BLOG DETAILS SECTION -->
    <section class="blog-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0 ">
                    <div class="blog-box">
                        <div class="img-box">
                            <img src="{{ $singleItem['image'] }}" alt="{{ $singleItem['title'] }}" class="img-fluid" />
                            <span class="author">Admin</span>
                        </div>
                        <div class="text-box">
                            <h5 class="title">
                                {{ $singleItem['title'] }}
                            </h5>
                            <span class="date"> {{ $singleItem['date'] }}</span>
                            <p class="description">
                                @lang($singleItem['description'])
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @if (isset($popularContentDetails['blog']))
                        <div class="custom-card p-3 bg-gradient">
                            <h4>@lang('Recent Post')</h4>
                            @foreach ($popularContentDetails['blog']->sortDesc() as $data)
                                <div class="w-100 d-block mb-3 py-2">
                                    <div class="d-flex justify-content-between recent-blog">
                                        <img class="w-25"
                                             src="{{ getFile(config('location.content.path') . @$data->content->contentMedia->description->image) }}"
                                             alt="{{ @$data->description->title }}">

                                        <div class="w-75 ms-2">
                                            <a href="#">{{ \Str::limit($data->description->title, 25) }}</a>

                                            <p>{{ dateTime($data->created_at) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
