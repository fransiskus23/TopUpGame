@extends($theme . 'layouts.user')
@section('title', __('2 Step Security'))

@section('content')

    <div class="dashboard-section padding-top padding-bottom overflow-hidden login-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    @if (auth()->user()->two_fa)
                        <div class="col-md-12 shadow-none p-3 bg-gradient rounded">
                            <div class="contact-box">
                                <div class="card-header">
                                    <h5 class="card-title">@lang('Two Factor Authenticator')</h5>
                                </div>

                                <div class="form-group">
                                    <div class="form-group form-box">
                                        <div class="input-group append">
                                            <input type="text" value="{{ $previousCode }}" class="form-control"
                                                   id="referralURL" readonly>
                                            <button class="game-btn copytext" type="button" id="copyBoard"
                                                    onclick="copyFunction()"><i class="fa fa-copy"></i> @lang('Copy')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mx-auto text-center my-3">
                                    <img class="mx-auto w-30" src="{{ $previousQR }}">
                                </div>

                                <div class="form-group mx-auto text-center">
                                    <a href="javascript:void(0)" class="btn btn-custom" data-bs-toggle="modal"
                                       data-bs-target="#disableModal">@lang('Disable Two Factor Authenticator')</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-12 shadow-none p-3 bg-gradient rounded">
                            <div class="contact-box">
                                <div class="card-header">
                                    <h5 class="card-title">@lang('Two Factor Authenticator')</h5>
                                </div>

                                <div class="form-group form-box">
                                    <div class="input-group append">
                                        <input type="text" value="{{ $secret }}" class="form-control"
                                               id="referralURL" readonly>
                                        <button class="game-btn copytext" type="button" id="copyBoard"
                                                onclick="copyFunction()"><i class="fa fa-copy"></i> @lang('Copy')
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group mx-auto text-center">
                                    <img class="w-30 mx-auto" src="{{ $qrCodeUrl }}">
                                </div>

                                <div class="form-group mx-auto text-center">
                                    <a href="javascript:void(0)" class="btn btn-custom mt-3" data-bs-toggle="modal"
                                       data-bs-target="#enableModal">@lang('Enable Two Factor Authenticator')</a>
                                </div>
                            </div>

                        </div>
                    @endif
                </div>

                <div class="col-lg-6">
                    <div class="col-md-12 custom-card p-3 bg-gradient">
                        <div class="contact-box">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Google Authenticator')</h5>
                            </div>
                            <div class="card-body">

                                <h6 class="text-uppercase my-3">@lang('Use Google Authenticator to Scan the QR code  or use the code')</h6>

                                <p class="p-5">@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.')</p>
                                <div class="submit-btn-wrapper text-center text-md-left">
                                    <a class="btn btn-site"
                                       href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                                       target="_blank">@lang('DOWNLOAD APP')</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!--Enable Modal -->
    <div id="enableModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">

            <!-- Modal content-->
            <div class="modal-content form-block">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h5 class="modal-title">@lang('Verify Your OTP')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>
                <form action="{{ route('user.twoStepEnable') }}" method="POST">
                    @csrf
                    <div class="modal-body-custom">
                        <div class="withdraw-detail">
                            <input type="hidden" name="key" value="{{ $secret }}">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg bg-dark text-white" name="code"
                                       placeholder="@lang('Enter Google Authenticator Code')">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer-custom">
                        <button type="button" class="btn btn-dark mx-2" data-bs-dismiss="modal">@lang('Close')</button>

                        <button type="submit" class="btn btn-success">@lang('Verify')</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <!--Disable Modal -->
    <div id="disableModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">

            <!-- Modal content-->
            <div class="modal-content form-block">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h5 class="modal-title">@lang('Verify Your OTP to Disable')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('user.twoStepDisable') }}" method="POST">
                    @csrf
                    <div class="modal-body-custom">
                        <div class="withdraw-detail">

                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg bg-dark text-white" name="code"
                                       placeholder="@lang('Enter Google Authenticator Code')">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn btn-dark mx-2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success">@lang('Verify')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>




@endsection



@push('script')
    @if(count($errors) > 0 )
        @foreach($errors->all() as $key => $error)
            <script>
                Notiflix.Notify.Failure("{{$error}}");
            </script>
        @endforeach
    @endif


    <script>
        function copyFunction() {
            var copyText = document.getElementById("referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.Success(`Copied: ${copyText.value}`);
        }
    </script>
@endpush
