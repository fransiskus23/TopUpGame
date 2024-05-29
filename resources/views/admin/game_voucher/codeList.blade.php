@extends('admin.layouts.app')
@section('title')
    @lang($voucherService->name)
@endsection
@section('content')



    <div class="container-fluid">
        <div class="row">

            <div class="col-md-3">
                <div class="card  shadow">
                    <div class="card-body">
                        <h4 class="card-title">`@lang(@optional($voucherService->voucher)->details->name)
                            ` @lang('Services')</h4>


                        <div class="list-group ">
                            @if($voucherService->voucher)
                                @foreach(optional($voucherService->voucher)->services as $key => $data)
                                    <a href="{{route('admin.gameVoucher.serviceCode',[$data->id])}}"
                                       class="list-group-item  d-flex justify-content-between @if($voucherService->name == $data->name) active @endif"
                                       title="{{$data->name}}"
                                    >
                                        {{Str::limit($data->name,30)}}
                                    </a>
                                @endforeach
                            @else
                                <a href="javascript:void(0)"
                                   class="list-group-item ">@lang('No Data Found')</a>
                            @endif
                        </div>


                    </div>
                </div>


                <div class="card shadow my-3">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <h3 class="card-title">@lang('Bulk Upload')</h3>
                            <a href="{{route('admin.VouchersampleFiles')}}" class="btn btn-sm btn-success btn-rounded"><i class="fa fa-download"></i> @lang('Sample')</a>
                        </div>

                        <form action="{{route('admin.uploadBulkVoucherCode')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="serviceId">@lang('Service')</label>
                                <select name="serviceId" id="serviceId" class="form-control">
                                    <option value="" selected disabled>@lang('Select a Service')</option>
                                    @if($voucherService->voucher)
                                        @foreach(optional($voucherService->voucher)->services as $key => $data)
                                            <option value="{{$data->id}}" @if($serviceId == $data->id) selected @endif>@lang($data->name)</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" name="file" class="custom-file-input" id="inputGroupFile01">
                                        <label class="custom-file-label" for="inputGroupFile01"></label>
                                    </div>
                                </div>

                                <span class="text-secondary">@lang('Upload your .csv file')</span>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> @lang('Upload')
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card  shadow">

                    <div class="card-body">


                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <button class="btn btn-sm btn-primary mr-2" type="button" data-toggle="modal"
                                        data-target="#add_code"><i class="fa fa-plus-circle"></i> @lang('Add Code')
                                </button>


                            </div>


                            <div class="dropdown mb-2 text-right">


                                <button class="btn btn-sm  btn-dark dropdown-toggle" type="button"
                                        id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span><i class="fas fa-bars pr-2"></i> @lang('Action')</span>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <button class="dropdown-item" type="button" data-toggle="modal"
                                            data-target="#all_active">@lang('Active')</button>
                                    <button class="dropdown-item" type="button" data-toggle="modal"
                                            data-target="#all_inactive">@lang('Inactive')</button>
                                    <button class="dropdown-item" type="button" data-toggle="modal"
                                            data-target="#all_delete">@lang('Delete')</button>
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
                                <th scope="col">@lang('No.')</th>
                                <th scope="col">@lang('Voucher')</th>
                                <th scope="col">@lang('Service')</th>
                                <th scope="col">@lang('Code')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($voucherServiceCode as $key => $item)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" id="chk-{{ $item->id }}"
                                               class="form-check-input row-tic tic-check" name="check"
                                               value="{{$item->id}}"
                                               data-id="{{ $item->id }}">
                                        <label for="chk-{{ $item->id }}"></label>
                                    </td>
                                    <td data-label="@lang('No.')">{{++$key}}</td>
                                    <td data-label="@lang('Voucher')">{{$item->gameVoucher->name}}</td>
                                    <td data-label="@lang('Voucher')">{{$item->voucherService->name}}</td>
                                    <td data-label="@lang('Code')">{{$item->code}}</td>
                                    <td data-label="@lang('Status')">
                                        <?php echo $item->statusMessage; ?>
                                    </td>
                                    <td data-label="@lang('Action')">

                                        <div class="dropdown show dropup">
                                            <a class="dropdown-toggle p-3" href="#" id="dropdownMenuLink"
                                               data-toggle="dropdown"
                                               aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">


                                                <button class="dropdown-item edit-button" data-toggle="modal"

                                                        data-target="#edit_code"
                                                        data-route="{{route('admin.voucherServiceCodeUpdate',$item->id)}}"
                                                        data-resource="{{$item}}"
                                                >
                                                    <i class="fa fa-edit text-warning pr-2"
                                                       aria-hidden="true"></i> @lang('Edit')
                                                </button>


                                                <a class="dropdown-item deleteBtn notiflix-confirm" href="javascript:void(0)"
                                                   data-target="#delete-modal"
                                                   data-route="{{ route('admin.voucherServiceCodeDelete', $item->id) }}"
                                                   data-toggle="modal"
                                                >
                                                    <i class="fa fa-trash-alt text-danger pr-2"
                                                       aria-hidden="true"></i> @lang('Delete')
                                                </a>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="8">@lang('No Data Found')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Add Delete Modal Start -->
    <div class="modal fade" id="all_delete" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Code Delete Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang("Are you really want to Delete Code")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('No')</span></button>
                    <form action="" method="post">
                        @csrf
                        <a href="" class="btn btn-primary delete-yes"><span>@lang('Yes')</span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="all_active" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Code Active Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang("Are you really want to active the Services")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('No')</span></button>
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
                    <h5 class="modal-title">@lang('Code DeActive Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang("Are you really want to Deactive the Services")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('No')</span></button>
                    <form action="" method="post">
                        @csrf
                        <a href="" class="btn btn-primary inactive-yes"><span>@lang('Yes')</span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Code Modal Start -->
    <div class="modal fade" id="add_code" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><i class="fa fa-plus-circle"></i> @lang('Add New Code')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <form action="{{route('admin.voucherServiceCodeStore',[$voucherId,$serviceId])}}" method="post">
                    @csrf
                    <div class="modal-body">


                        <div class="addedField">
                            <div class="form-group">
                                <div class="input-group">
                                    <input name="code[]" class="form-control " type="text" value="" required
                                           placeholder="{{ trans('Code') }}">

                                    <span class="input-group-btn">
                                            <button class="btn btn-primary" id="generate" type="button">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                </div>
                            </div>
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
    <!-- Add Code Modal End -->

    <!-- Edit Code Modal Start -->
    <div class="modal fade" id="edit_code" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><i class="fa fa-plus-circle"></i> @lang('Update Code')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <form action="" method="post" class="update-action">
                    @csrf
                    @method('put')
                    <div class="modal-body">


                        <div class="form-group">
                            <label for="name" class="font-weight-bold"> @lang('Code') </label>
                            <input type="text" name="code"
                                   class="form-control edit-name"
                                   value="" required>

                            @error('code')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">

                            <label for="edit-status"> @lang('status') </label>
                            <input
                                data-toggle="toggle" id="edit-status" data-onstyle="success"
                                data-offstyle="info" data-on="Active" data-off="Deactive"
                                data-width="100%"
                                type="checkbox" name="status">
                            @error('status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class="btn btn-primary"><span>@lang('Update')</span></button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Code Modal End -->

    <!-- Delete Modal Start -->
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
    </div>
    <!-- Delete Modal End -->
@endsection
@push('style-lib')
    <link href="{{ asset('assets/admin/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/datatable-basic.init.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function (e) {

            $("#generate").on('click', function () {
                var form = `
                <div class="form-group">
                    <div class="input-group">
                        <input name="code[]" class="form-control " type="text" value="" required placeholder="{{ trans('Code') }}">

                        <span class="input-group-btn">
                            <button class="btn btn-danger delete_desc" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                        </span>
                    </div>
                </div>
            `;

                $('.addedField').append(form)
            });


            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.form-group').remove();
            });

            $(document).on('click', '.edit-button', function () {
                $('.update-action').attr('action', $(this).data('route'))
                var obj = $(this).data('resource');
                $('.edit-name').val(obj.code);

                if (obj.status == 1) {
                    $('#edit-status').bootstrapToggle('on')
                } else {
                    $('#edit-status').bootstrapToggle('off')
                }


            });

            $(document).on('click', '#check-all', function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });

            $(document).on('change', ".row-tic", function () {
                let length = $(".row-tic").length;
                let checkedLength = $(".row-tic:checked").length;
                if (length == checkedLength) {
                    $('#check-all').prop('checked', true);
                } else {
                    $('#check-all').prop('checked', false);
                }
            });

            //dropdown menu is not working
            $(document).on('click', '.dropdown-menu', function (e) {
                e.stopPropagation();
            });


            //multiple active
            $(document).on('click', '.active-yes', function (e) {
                e.preventDefault();
                var allVals = [];
                $(".row-tic:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });

                var strIds = allVals;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{ route('admin.voucherServiceCode.active') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();

                    },
                });
            });

            //multiple deactive
            $(document).on('click', '.inactive-yes', function (e) {
                e.preventDefault();
                var allVals = [];
                $(".row-tic:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });

                var strIds = allVals;
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{ route('admin.voucherServiceCode.inactive') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();

                    }
                });
            });

            //multiple Delete
            $(document).on('click', '.delete-yes', function (e) {
                e.preventDefault();
                var allVals = [];
                $(".row-tic:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });

                var strIds = allVals;
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{ route('admin.voucherServiceCode.delete') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();

                    }
                });
            });

            $(document).on('click', '.deleteBtn', function (e) {
                $('.deleteRoute').attr('action', $(this).data('route'))
            })

        });
    </script>
@endpush
