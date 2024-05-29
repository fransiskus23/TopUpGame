@if(0 < count($sellPost))
<!-- Sell Post SECTION -->
<section id="topup" class="topup-section">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="header-text-link">
                    @if(isset($templates['sell-post'][0]) && $postSell = $templates['sell-post'][0])
                        <h2>@lang($postSell->description->title)</h2>
                    @endif
                    <a href="{{route('buy')}}">
                        @lang('Shop more')
                        <i class="fas  fa-angle-double-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row" data-aos-duration="800" data-aos="zoom-in" data-aos-anchor-placement="center-bottom">
            @forelse ($sellPost as $key => $item)
                @if(0 < count($item->activePost))
                <div class="col-lg-2 col-md-3 col-sm-4 col-4">
                    <div class="img-box">
                        <a href="{{route('buy').'?sortByCategory='.$item->id}}">
                                <img src="{{getFile(config('location.sellPostCategory.path').@$item->image)}}" alt="..."
                                      class="img-fluid"/>

                        </a>
                        <p class="pt-2 mb-0">
                            {{\Illuminate\Support\Str::limit(optional($item->details)->name,20)}}
                        </p>
                    </div>
                </div>
                @endif
            @empty
            @endforelse
        </div>
    </div>
</section>
@endif
