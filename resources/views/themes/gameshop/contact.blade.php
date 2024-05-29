@extends($theme . 'layouts.app')
@section('title', trans($title))

@section('content')

    <!-- CONTACT SECTION -->
    <section class="contact-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 pe-md-5 mb-5 mb-md-0">
                    <h2>@lang(@$contact->heading)</h2>
                    <p>
                        @lang(@$contact->sub_heading)
                    </p>
                    <div class="mt-5">
                        <div class="d-flex align-items-baseline mb-4">
                            <div class="me-3">
                                <img src="{{ asset($themeTrue . '/images/icon/email2.png') }}" alt="..." />
                            </div>
                            <div class="text">
                                <h5>@lang('email')</h5>
                                <span>@lang(@$contact->email)</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline mb-4">
                            <div class="me-3">
                                <img src="{{ asset($themeTrue . '/images/icon/phone2.png') }}" alt="..." />
                            </div>
                            <div class="text">
                                <h5>@lang('Phone')</h5>
                                <span>@lang(@$contact->phone)</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline mb-4">
                            <div class="me-3">
                                <img src="{{ asset($themeTrue . '/images/icon/address.png') }}" alt="..." />
                            </div>
                            <div class="text">
                                <h5>@lang('address')</h5>
                                <span>@lang(@$contact->address)</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="contact-box">
                        <form action="{{ route('contact.send') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label">@lang('name')
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                    id="exampleFormControlInput1" placeholder="@lang('John doe')" />
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label">@lang('Your Email')
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                    id="exampleFormControlInput1" placeholder="@lang('name@example.com')" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label">@lang('Subject')
                                </label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="form-control"
                                    id="exampleFormControlInput1" placeholder="@lang('Subject')" />
                                @error('subject')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">@lang('Your message')</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" name="message"
                                    placeholder="@lang('Opinion')"></textarea>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button class="game-btn" type="submit">
                                {{ trans('Submit now') }}
                                <img src="{{ asset($themeTrue . '/images/icon/arrow-white.png') }}" alt="..." />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
