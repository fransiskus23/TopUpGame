@extends('admin.layouts.app')
@section('title')
    @lang('Basic Controls')
@endsection
@section('content')

    <div class="alert alert-warning my-5 m-0 m-md-4" role="alert">
        <i class="fas fa-info-circle mr-2"></i> @lang("If you get 500(server error) for some reason, please turn on <b>Debug Mode</b> and try again. Then you can see what was missing in your system.")
    </div>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <form method="post" action="" class="needs-validation base-form">
                @csrf
                <div class="row">
                    <div class="form-group col-md-2">
                        <label class="text-dark">@lang('Site Title')</label>
                        <input type="text" name="site_title"
                               value="{{ old('site_title') ?? $control->site_title ?? 'Site Title' }}"
                               class="form-control ">

                        @error('site_title')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-2">
                        <label class="text-dark">@lang('Base Color')</label>
                        <input type="color" name="base_color"
                               value="{{ old('base_color') ?? $control->base_color ?? '#ff8503' }}"
                               required="required" class="form-control ">
                        @error('base_color')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-2">
                        <label class="text-dark">@lang('Secondary Color')</label>
                        <input type="color" name="base_secondary"
                               value="{{ old('base_secondary') ?? $control->base_secondary ?? '#ff7843' }}"
                               required="required" class="form-control ">
                        @error('base_secondary')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                    <div class="form-group col-md-2 col-12">
                        <label class="text-dark">@lang('Base Currency')</label>
                        <input type="text" name="currency" value="{{ old('currency') ?? $control->currency ?? 'USD' }}"
                               required="required" class="form-control ">

                        @error('currency')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-2 col-12">
                        <label class="text-dark">@lang('Currency Symbol')</label>
                        <input type="text" name="currency_symbol"
                               value="{{ old('currency_symbol') ?? $control->currency_symbol ?? '$' }}"
                               required="required" class="form-control ">

                        @error('currency_symbol')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-2 col-12">
                        <label class="text-dark">@lang('Fraction number')</label>
                        <input type="text" name="fraction_number"
                               value="{{ old('fraction_number') ?? $control->fraction_number ?? '2' }}"
                               required="required" class="form-control ">
                        @error('fraction_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-3 col-12">
                        <label class="text-dark">@lang('Paginate Per Page')</label>
                        <input type="text" name="paginate" value="{{ old('paginate') ?? $control->paginate ?? '2' }}"
                               required="required" class="form-control ">
                        @error('paginate')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-md-3">
                        <label class="text-dark">@lang('TimeZone')</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="time_zone">
                            <option hidden>{{ old('time_zone', $control->time_zone)?? 'Select Time Zone' }}</option>
                            @foreach ($control->time_zone_all as $time_zone_local)
                                <option value="{{ $time_zone_local }}">@lang($time_zone_local)</option>
                            @endforeach
                        </select>

                        @error('time_zone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                    <div class="form-group col-md-3">
                        <label class="text-dark">@lang('Sell Post Payment Lock Expired')</label>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">@lang('For')</span>
                            </div>
                            <input type="text" name="payment_expired"
                                   value="{{ old('payment_expired') ?? $control->payment_expired ?? 15 }}"
                                   class="form-control ">

                            <div class="input-group-append">
                                <span class="input-group-text">@lang('Minutes')</span>
                            </div>
                        </div>

                        @error('payment_expired')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-md-3">
                        <label class="text-dark">@lang('Sell Post Payment Release')</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">@lang('After')</span>
                            </div>
                            <input type="text" name="payment_released"
                                   value="{{ old('payment_released') ?? $control->payment_released ?? 7 }}"
                                   class="form-control ">

                            <div class="input-group-append">
                                <span class="input-group-text">@lang('Days')</span>
                            </div>
                        </div>

                        @error('payment_released')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-lg-3 col-md-6">
                        <label class="text-dark">@lang('Topup Module')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='top_up'>
                            <input type="checkbox" name="top_up" class="custom-switch-checkbox"
                                   id="top_up"
                                   value="0" <?php if ($control->top_up == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="top_up">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-lg-3 col-md-6">
                        <label class="text-dark">@lang('Voucher Module')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='voucher'>
                            <input type="checkbox" name="voucher" class="custom-switch-checkbox"
                                   id="voucher"
                                   value="0" <?php if ($control->voucher == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="voucher">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group col-lg-3 col-md-6">
                        <label class="text-dark">@lang('Gift Card Module')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='gift_card'>
                            <input type="checkbox" name="gift_card" class="custom-switch-checkbox"
                                   id="gift_card"
                                   value="0" <?php if ($control->gift_card == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="gift_card">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>




                    <div class="form-group col-lg-3 col-md-6">
                        <label class="text-dark">@lang('Sell Post Module')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='sell_post'>
                            <input type="checkbox" name="sell_post" class="custom-switch-checkbox"
                                   id="sell_post"
                                   value="0" <?php if ($control->sell_post == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="sell_post">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>


                </div>

                <div class="row">

                    <div class="form-group col-sm-6 col-md-4 col-lg-3 ">
                        <label class="text-dark">@lang('Strong Password')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='strong_password'>
                            <input type="checkbox" name="strong_password" class="custom-switch-checkbox"
                                   id="strong_password"
                                   value="0" {{($control->strong_password == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="strong_password">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4 col-lg-3 ">
                        <label class="text-dark">@lang('Registration')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='registration'>
                            <input type="checkbox" name="registration" class="custom-switch-checkbox"
                                   id="registration"
                                   value="0" {{($control->registration == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="registration">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group col-lg-3 col-md-6">
                        <label class="text-dark">@lang('Cron Set Up Pop Up')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='cron_set_up_pop_up'>
                            <input type="checkbox" name="cron_set_up_pop_up" class="custom-switch-checkbox"
                                   id="cron_set_up_pop_up"
                                   value="0" <?php if ($control->is_active_cron_notification == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="cron_set_up_pop_up">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-3 ">
                        <label class="text-dark">@lang('Debug Mode')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='error_log'>
                            <input type="checkbox" name="error_log" class="custom-switch-checkbox"
                                   id="error_log"
                                   value="0" <?php if ($control->error_log == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="error_log">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>


                </div>


                <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                            class="fas fa-save pr-2"></i> @lang('Save Changes')</span></button>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        "use strict";
        $(document).ready(function () {
            $('select').select2({
                selectOnClose: true
            });
        });
    </script>
@endpush
