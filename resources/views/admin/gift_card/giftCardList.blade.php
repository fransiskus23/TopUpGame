@extends('admin.layouts.app')
@section('title')
    @lang('Gift Card List')
@endsection

@section('content')
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">

        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
                <div>
                    <a href="{{ route('admin.giftCardCreate') }}" class="btn btn-sm btn-primary mr-2">
                        <i class="fa fa-plus-circle"></i> @lang('Create New')
                    </a>
                    <button class="btn btn-sm btn-primary mr-2" type="button" data-toggle="modal" data-target="#add_service"><i
                            class="fa fa-list"></i> @lang('Add Service')
                    </button>
                </div>

                <div class="dropdown mb-2 text-right">
                    <button class="btn btn-sm  btn-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span><i class="fas fa-bars pr-2"></i> @lang('Action')</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item" type="button" data-toggle="modal"
                            data-target="#all_active">@lang('Active')</button>
                        <button class="dropdown-item" type="button" data-toggle="modal"
                            data-target="#all_inactive">@lang('Inactive')</button>
                    </div>
                </div>


            </div>


            <div class="table-responsive">
                <table class="categories-show-table table table-hover table-striped table-bordered" id="zero_config">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center">
                                <input type="checkbox" class="form-check-input check-all tic-check" name="check-all"
                                    id="check-all">
                                <label for="check-all"></label>
                            </th>

                            <th scope="col">@lang('SL No.')</th>
                            <th scope="col">@lang('Name')</th>
                            <th scope="col">@lang('Active Service')</th>
                            <th scope="col">@lang('Active Codes')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($manageCard as $item)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" id="chk-{{ $item->id }}"
                                        class="form-check-input row-tic tic-check" name="check"
                                        value="{{ $item->id }}" data-id="{{ $item->id }}">
                                    <label for="chk-{{ $item->id }}"></label>
                                </td>

                                <td data-label="@lang('SL No.')">{{ $loop->index + 1 }}</td>
                                <td data-label="@lang('Name')">
                                    <div class="d-flex no-block align-items-center">
                                        <div class="mr-3"><img
                                                src="{{ getFile(config('location.giftCard.path') . $item->thumb) }}"
                                                alt="user" class="rounded-circle" width="45" height="45"></div>
                                        <div class="mr-3">
                                            <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional($item->details)->name)</h5>
                                        </div>
                                        <div class="">
                                            @if ($item->discount_status == 1)
                                                <span
                                                    class="badge badge-pill badge-success">{{ getAmount($item->discount_amount) }}
                                                    {{ $item->discount_type == 1 ? '%' : config('basic.currency') }}</span>
                                            @endif
                                        </div>

                                    </div>

                                </td>
                                <td data-label="@lang('Active Service')">
                                    <span class="badge badge-info">{{ $item->active_services_count }}</span>
                                </td>
                                <td data-label="@lang('Active Codes')">
                                    <span class="badge badge-info">{{ $item->active_codes_count }}</span>
                                </td>

                                <td data-label="@lang('Status')">
                                    <?php echo $item->statusMessage; ?>
                                </td>


                                <td data-label="@lang('Action')">

                                    <div class="dropdown show dropup">
                                        <a class="dropdown-toggle p-3" href="#" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                            <button class="dropdown-item servicesInfo" type="button"
                                                data-resource="{{ $item }}"
                                                data-services="{{ $item->services }}" data-toggle="modal"
                                                data-target="#serviceList">
                                                <i class="fa fa-gamepad text-primary pr-2" aria-hidden="true"></i>
                                                @lang('Service List')
                                            </button>


                                            <a class="dropdown-item" href="{{ route('admin.giftCardEdit', $item->id) }}">
                                                <i class="fa fa-edit text-warning pr-2" aria-hidden="true"></i>
                                                @lang('Edit')
                                            </a>


                                            <a class="dropdown-item notiflix-confirm" href="javascript:void(0)"
                                                data-target="#delete-modal"
                                                data-route="{{ route('admin.giftCardDelete', $item->id) }}"
                                                data-toggle="modal">
                                                <i class="fa fa-trash-alt text-danger pr-2" aria-hidden="true"></i>
                                                @lang('Delete')
                                            </a>
                                        </div>
                                    </div>

                                </td>
                            </tr>

                        @empty
                            <tr align="center">
                                <td colspan="100%">@lang('No Data Found')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Service Modal Start -->
    <div class="modal fade" id="add_service" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><i class="fa fa-plus-circle"></i> @lang('Add New Service')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <form action="{{ route('admin.giftCardServicesStore') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="font-weight-bold"> @lang('Name') </label>
                            <input type="text" name="name" class="form-control" value="" required>

                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="price" class="font-weight-bold"> @lang('Price') </label>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ config('basic.currency_symbol') }}</span>
                                </div>
                                <input type="text" name="price" class="form-control edit-price"
                                    value="{{ old('price') }}" required>
                            </div>
                            @error('price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">@lang('Gift Card') </label>
                            <select class="form-control" name="gift_card_id" aria-label=".form-select-lg example"
                                required>

                                <option value="" selected disabled>@lang('Select Card')</option>
                                @foreach ($manageCard as $item)
                                    <option value="{{ $item->details->gift_cards_id }}">{{ $item->details->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group">

                            <label for="edit-status" class="font-weight-bold"> @lang('Status') </label>
                            <input data-toggle="toggle" id="edit-status" data-onstyle="success" data-offstyle="info"
                                data-on="Active" data-off="Deactive" data-width="100%" type="checkbox" name="status">
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class="btn btn-primary"><span>@lang('Add')</span></button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Service Modal End -->


    <div class="modal fade" id="all_active" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Gift Card Active Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you really want to active the Gift Card')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-dismiss="modal"><span>@lang('No')</span></button>
                    <form action="" method="post">
                        @csrf
                        <a href="" class="btn btn-primary active-yes"><span>@lang('Yes')</span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="all_inactive" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Gift Card DeActive Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you really want to Deactive the Gift Card')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-dismiss="modal"><span>@lang('No')</span></button>
                    <form action="" method="post">
                        @csrf
                        <a href="" class="btn btn-primary inactive-yes"><span>@lang('Yes')</span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">@lang('Delete Confirmation')
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure to delete this?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                    <form action="" method="post" class="deleteRoute">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Service List Modal -->
    <div class="modal fade" id="serviceList" role="dialog">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title service-title">@lang('Service list')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Price')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="result-body">
                        </tbody>
                    </table>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-dismiss="modal"><span>@lang('Close')</span></button>
                </div>
            </div>
        </div>
    </div>




    <!-- Edit Service Modal Start -->
    <div class="modal fade z9999" id="update_service" role="dialog" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><i class="fa fa-plus-circle"></i> @lang('Edit Service')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <form action="" method="post" class="service-edit_route">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="font-weight-bold"> @lang('Name') </label>
                            <input type="text" name="name" class="form-control service-name" value=""
                                required>

                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="price" class="font-weight-bold"> @lang('Price') </label>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ config('basic.currency_symbol') }}</span>
                                </div>
                                <input type="text" name="price" class="form-control service-price"
                                    value="{{ old('price') }}" required>
                            </div>
                            @error('price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">@lang('Gift Card') </label>
                            <select class="form-control service-game_card_id" name="gift_card_id" required>
                                <option value="" selected disabled>@lang('Select Gift Card')</option>
                                @foreach ($manageCard as $item)
                                    <option value="{{ optional($item->details)->gift_cards_id }}">
                                        {{ optional($item->details)->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group">

                            <label for="service-status" class="font-weight-bold"> @lang('Status') </label>
                            <input data-toggle="toggle" id="service-status" data-onstyle="success" data-offstyle="info"
                                data-on="Active" data-off="Deactive" data-width="100%" type="checkbox" name="status">
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')
                        </button>
                        <button type="submit" class="btn btn-primary">@lang('Update')</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Service Modal End -->
@endsection
@push('style-lib')
    <link href="{{ asset('assets/admin/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/datatable-basic.init.js') }}"></script>


    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp
        <script>
            "use strict";
            @foreach ($errors as $error)
                Notiflix.Notify.Failure("{{ trans($error) }}");
            @endforeach
        </script>
    @endif

    <script>
        'use strict'
        $(document).ready(function() {
            $('.notiflix-confirm').on('click', function() {
                var route = $(this).data('route');
                $('.deleteRoute').attr('action', route)
            })
        });

        $(document).on('click', '.servicesInfo', function() {
            var resource = $(this).data('resource');
            $('.service-title').html(`${resource.details.name}`)
            var services = $(this).data('services');
            var output = [];
            if (0 < services.length) {
                services.map(function(obj, i) {
                    console.log(obj)
                    var tr = `
                    <tr>
                        <td scope="row">${++i}</td>
                        <td>${(obj).name}</td>
                        <td>{{ config('basic.currency_symbol') }}${obj.price}</td>
                        <td>${obj.statusMessage}</td>
                        <td>
                            <button
                                      data-id="${obj.id}"
                                      data-name="${obj.name}"
                                      data-price="${parseInt(obj.price)}"
                                      data-status="${obj.status}"
                                      data-edit_route="${obj.editRoute}"
                                      data-gift_cards_id="${obj.gift_cards_id}"
                                      data-toggle="modal"
                                      data-target="#update_service"
                                      class="btn btn-sm btn-outline-primary update_service" type="button">@lang('Edit')</button>
                            <a  href="${obj.serviceInfoRoute}" target="_blank"
                                      class="btn btn-sm btn-outline-success">@lang('Gift Card Code')</a>
                        </td>
                    </tr>`;

                    output[i] = tr;
                });

            } else {
                output[0] = `
                        <tr>
                            <td colspan="100%" class=""text-center>@lang('No Data Found')</td>
                        </tr>`;
            }



            $('.result-body').html(output);
        });

        $(document).on('click', '#check-all', function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $(document).on('change', ".row-tic", function() {
            let length = $(".row-tic").length;
            let checkedLength = $(".row-tic:checked").length;
            if (length == checkedLength) {
                $('#check-all').prop('checked', true);
            } else {
                $('#check-all').prop('checked', false);
            }
        });

        //dropdown menu is not working
        $(document).on('click', '.dropdown-menu', function(e) {
            e.stopPropagation();
        });


        //multiple active
        $(document).on('click', '.active-yes', function(e) {
            e.preventDefault();
            var allVals = [];
            $(".row-tic:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });

            var strIds = allVals;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{ route('admin.giftCard.active') }}",
                data: {
                    strIds: strIds
                },
                datatType: 'json',
                type: "post",
                success: function(data) {
                    location.reload();

                },
            });
        });

        //multiple deactive
        $(document).on('click', '.inactive-yes', function(e) {
            e.preventDefault();
            var allVals = [];
            $(".row-tic:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });

            var strIds = allVals;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{ route('admin.giftCard.inactive') }}",
                data: {
                    strIds: strIds
                },
                datatType: 'json',
                type: "post",
                success: function(data) {
                    location.reload();

                }
            });
        });


        $(document).on('click', '.update_service', function() {

            $('.service-edit_route').attr('action', $(this).data('edit_route'));

            $('.service-name').val($(this).data('name'));
            $('.service-price').val($(this).data('price'));
            $('.service-game_card_id').val($(this).data('gift_cards_id'));

            if ($(this).data('status') == 1) {
                $('#service-status').bootstrapToggle('on')
            } else {
                $('#service-status').bootstrapToggle('off')
            }
        })
    </script>
@endpush
