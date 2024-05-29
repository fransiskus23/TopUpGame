@if(0 <count($top_up_games))
<!-- TOP UP SECTION -->
<section id="topup" class="topup-section">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="header-text-link">
                    @if(isset($templates['top-up'][0]) && $topUp = $templates['top-up'][0])
                       <h2>@lang(optional($topUp->description)->title)</h2>
                    @endif
                    <a href="{{route('shop').'?sortByCategory=topUp'}}">
                        @lang('Shop more')
                        <i class="fas  fa-angle-double-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row" data-aos-duration="800" data-aos="zoom-in" data-aos-anchor-placement="center-bottom">
            @forelse ($top_up_games as $key => $item)

                <div class="col-lg-2 col-md-3 col-sm-4  col-4">
                    <div class="img-box">
                        <a href="{{route('topUp.details',[slug(@$item->details->name??'top-up-details'),$item->id])}}">
                            <img src="{{getFile(config('location.category.path').@$item->thumb)}}" alt="..."
                                 title="{{optional($item->details)->name}}" class="img-fluid"/>

                        </a>

                        <div class="tags">
                            @if($item->discount_amount)
                                @if($item->discount_type =='0')
                                    <span>{{$item->discount_amount}}</span>
                                @else
                                    <span>{{$item->discount_amount}}%</span>
                                @endif

                            @endif
                            @if($item->featured=='1')
                                <span>@lang('featured')</span>
                            @endif
                        </div>
                        <p class="pt-2 mb-0">
                            {{\Illuminate\Support\Str::limit(optional($item->details)->name,15)}}
                        </p>
                    </div>
                </div>
            @empty
            @endforelse

        </div>
    </div>
</section>
@endif
