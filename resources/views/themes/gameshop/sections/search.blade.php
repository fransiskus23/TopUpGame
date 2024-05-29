<!-- SEARCH AREA -->
<section class="search-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form action="{{route('shop')}}" method="get">
                    <div
                        class="input-box"
                        data-aos-duration="800"
                        data-aos="fade-up"
                        data-aos-anchor-placement="center-bottom"
                    >
                        <div class="input-group">
                            <select name="sortByCategory"
                                    class="form-select"
                                    aria-label="Default select example"
                            >
                                <option selected>@lang('Select Category')</option>
                                @if(config('basic.top_up'))
                                    <option value="topUp">@lang('top up')</option>
                                @endif
                                @if(config('basic.voucher'))
                                    <option value="voucher">@lang('voucher')</option>
                                @endif
                                @if(config('basic.gift_card'))
                                    <option value="giftCard">@lang('gift card')</option>
                                @endif
                            </select>
                            <input
                                type="text"
                                name="search"
                                value="{{old('search',request()->search)}}"
                                class="form-control"
                                aria-label="Text input with dropdown button"
                                placeholder="Find your need"
                            />
                            <button class="search-btn">
                                <img src="{{asset($themeTrue).'/images/icon/search.png'}}" alt="..."/>
                            </button>
                        </div>
                        <img
                            class="spartan img-fluid"
                            src="{{asset($themeTrue).'/images/spartan.png'}}"
                            alt="..."
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
