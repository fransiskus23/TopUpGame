@extends($theme.'layouts.user')
@section('title')
    @lang('Sell Post List')
@endsection
@section('content')
    <div class="container login-section">
        <div class="row justify-content-between bg-gradient">
            <div class="col-md-12">
                <div class="contact-box mb-3 mx-2">
                    <form action="{{route('user.sellPost.search')}}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <input type="text" name="title" value="{{@request()->title}}" class="form-control"
                                           placeholder="@lang('Search for Title')">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <input type="date" class="form-control" name="datetrx" id="datepicker">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-2 h-fill">
                                    <button type="submit" class="game-btn w-100">
                                        <i class="fas fa-search" aria-hidden="true"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive table-custom">
                <table class="table table-bordered transection__table mt-5" id="service-table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('No.')</th>
                        <th scope="col">@lang('Title')</th>
                        <th scope="col">@lang('Category')</th>
                        <th scope="col">@lang('Price')</th>
                        <th scope="col">@lang('Status')</th>
                        <th scope="col">@lang('Date - Time')</th>
                        <th scope="col">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sellPost as $k => $row)
                        <tr>
                            <td data-label="@lang('No.')">{{++$k}}</td>
                            <td data-label="@lang('Title')">@lang($row->title)
                                @if($row->payment_status == 1)
                                    <span class="badge bg-info">@lang('sold')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Category')">@lang(@optional($row->category)->details->name)</td>
                            <td data-label="@lang('Price')"><span
                                    class="font-weight-bold">{{config('basic.currency_symbol')}}</span>{{getAmount($row->price)}}
                            </td>
                            <td data-label="@lang('Status')">
                                <?php echo $row->statusMessage; ?>
                            </td>

                            <td data-label="@lang('Date - Time')">{{dateTime($row->created_at, 'd M, Y h:i A')}}</td>
                            <td data-label="@lang('More')">
                                <div class="btn-group btn-group-sm" role="group">
                                    <div class="btn-group" role="group">
                                        <button id="offerActionBtn" type="button"
                                                class="btn text-white dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>

                                        <ul class="dropdown-menu" aria-labelledby="offerActionBtn">
                                            <li><a class="dropdown-item offerAccept"
                                                   href="{{Route('sellPost.details',[slug($row->title), $row->id])}}">
                                                    <i class="text-success fa fa-eye"></i> @lang('Details')
                                                </a>
                                            </li>

                                            @if($row->payment_status != 1)
                                                <li><a class="dropdown-item offerAccept"
                                                       href="{{Route('user.sellPostEdit',$row->id)}}">
                                                        <i class="text-info fa fa-edit"></i> @lang('Edit')
                                                    </a>
                                                </li>

                                                <li><a class="dropdown-item notiflix-confirm"
                                                       href="javascript:void(0)"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#delete-modal"
                                                       data-route="{{route('user.sellPostDelete',$row->id)}}">
                                                        <i class="text-danger fa fa-trash"></i> @lang('Delete')
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-danger" colspan="9">@lang('No Data Found')</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $sellPost->appends($_GET)->links($theme.'partials.pagination') }}
        </div>
    </div>
@endsection
@push('loadModal')
    <!-- Modal for Delete -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h4 class="modal-title" id="myModalLabel">@lang('Delete Confirm')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body-custom">

                    <p>@lang('Are you sure to delete this?')</p>

                </div>
                <div class="modal-footer-custom">
                    <form action="" method="post" class="deleteRoute">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-secondary">@lang('Yes')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endpush
@push('script')

    <script>
        "use strict";


        $(document).ready(function () {
            $('.notiflix-confirm').on('click', function () {
                var route = $(this).data('route');
                $('.deleteRoute').attr('action', route)
            })
        });
    </script>
@endpush
