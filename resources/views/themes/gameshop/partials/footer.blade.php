<!-- FOOTER SECTION -->
<footer class="footer-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="footer-box">
                    <a class="navbar-brand" href="#">
                        <img class="img-fluid" src="{{ getFile(config('location.logoIcon.path') . 'logo.png') }}"
                            alt="..." />
                    </a>
                    @if (isset($contactUs['contact-us'][0]) && ($contact = $contactUs['contact-us'][0]))
                        <p>
                            @lang(strip_tags(@$contact->description->footer_short_details))
                        </p>
                    @endif
                    @if (isset($contactUs['contact-us'][0]) && ($contact = $contactUs['contact-us'][0]))
                        <ul>
                            <li>
                                <i class="fa fa-phone"></i>
                                <span>@lang(@$contact->description->phone)</span>
                            </li>
                            <li>
                                <i class="fa fa-envelope-open"></i>
                                <span>@lang(@$contact->description->email)</span>
                            </li>
                            <li>
                                <i class="fa fa-location-arrow"></i>
                                <span>@lang(@$contact->description->address)</span>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ps-lg-5">
                <div class="footer-box">
                    <h5>@lang('Quick Links')</h5>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">@lang('Home')</a>
                        </li>
                        <li>
                            <a href="{{ route('about') }}">@lang('About')</a>
                        </li>
                        <li>
                            <a href="{{ route('blog') }}">@lang('Blog')</a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}">@lang('Contact')</a>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ps-lg-5">
                <div class="footer-box">
                    @isset($contentDetails['support'])
                        <h5>@lang('Useful Links')</h5>
                        <ul>

                            <li>
                                <a href="{{ route('faq') }}">@lang('FAQ')</a>
                            </li>
                            @foreach ($contentDetails['support'] as $data)
                                <li>
                                    <a
                                        href="{{ route('getLink', [slug($data->description->title), $data->content_id]) }}">@lang($data->description->title)</a>
                                </li>
                            @endforeach

                        </ul>
                    @endisset
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="footer-box">
                    <h5>@lang('subscribe newsletter')</h5>
                    <form action="{{ route('subscribe') }}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="@lang('Enter Email')" aria-label="Subscribe Newsletter" aria-describedby="basic-addon" />
                            <span class="input-group-text" id="basic-addon"><button type="submit"><img src="{{ asset($themeTrue . '/images/icon/paper-plane.png') }}" alt="..." /></button>
                            </span>
                        </div>

                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </form>

                    @if(isset($contentDetails['social']))
                    <div class="social-links mt-2">
                            @foreach($contentDetails['social'] as $data)
                                <a href="{{@$data->content->contentMedia->description->link}}" title="@lang($data->description->name)">
                                    <i class="{{@$data->content->contentMedia->description->icon}}"></i>
                                </a>
                            @endforeach
                    </div>

                    @endif
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6">
                    <p class="copyright">
                        @lang('Copyright © 2022') <a href="{{ route('home') }}">@lang($basic->site_title)</a>
                        @lang('All Rights Reserved')
                    </p>
                </div>

                <div class="col-md-6 language">
                    @forelse($languages as $lang)
                    <a href="{{route('language',$lang->short_name)}}">{{trans($lang->name)}}</a>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</footer>


<a href="#" class="scroll-top">
    <i class="fas fa-arrow-up"></i>
</a>
