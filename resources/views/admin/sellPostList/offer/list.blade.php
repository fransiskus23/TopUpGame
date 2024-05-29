@extends('admin.layouts.app')
@section('title')
    @lang("Offer List")
@endsection
@section('content')
    <div class="page-header card card-primary m-0 m-md-4 my-4 m-md-0 p-5 shadow">
        <div class="row justify-content-between">
            <div class="col-md-12">
                <form action="{{route('admin.sell.search')}}" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="title" value="{{@request()->title}}" class="form-control get-trx-id"
                                       placeholder="@lang('Title')">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="user_name" value="{{@request()->user_name}}" class="form-control get-username"
                                       placeholder="@lang('Username')">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="date" class="form-control" name="datetrx" id="datepicker"/>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="submit" class="btn waves-effect waves-light btn-primary"><i class="fas fa-search"></i> @lang('Search')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <table class="categories-show-table table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">@lang('No.')</th>
                    <th scope="col">@lang('User')</th>
                    <th scope="col">@lang('Title')</th>
                    <th scope="col">@lang('Category')</th>
                    <th scope="col">@lang('Amount')</th>
                    <th scope="col">@lang('Date - Time')</th>
                    <th scope="col">@lang('Status')</th>
                    <th scope="col">@lang('More')</th>
                </tr>
                </thead>
                <tbody>

                @forelse($sellPostOffer as $k => $row)
                    <tr>
                        <td data-label="@lang('No.')">{{++$k}}</td>
                        <td data-label="@lang('User')">

                            <a href="{{route('admin.user-edit',$row->user_id)}}">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img src="{{getFile(config('location.user.path').optional($row->user)->image) }}" alt="user" class="rounded-circle" width="45" height="45"></div>
                                    <div class="">
                                        <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional($row->user)->username)</h5>
                                        <span class="text-muted font-14">{{optional($row->user)->email}}</span>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td data-label="@lang('Category')">{{optional($row->sellPost)->title}}
                            @if(optional($row->sellPost)->payment_status == 1)
                                <span class="badge badge-pill badge-success">@lang('Sold')</span>
                                <span class="badge badge-pill badge-info">{{optional($row->sellPost->sellPostPayment)->transaction}}</span>
                            @endif
                        </td>
                        <td data-label="@lang('Category')">{{@optional($row->sellPost)->category->details->name}}</td>
                        <td data-label="@lang('Amount')" ><span class="font-weight-bold">{{config('basic.currency_symbol')}}</span>{{getAmount($row->amount)}}
                        </td>
                        <td data-label="@lang('Date - Time')">{{dateTime($row->created_at, 'd M, Y H:i')}}</td>
                        <td data-label="@lang('Status')">
                            <?php echo $row->sellPost->statusMessage; ?>
                        </td>
                        <td data-label="@lang('More')">
                            <div class="dropdown show dropup">
                                <a class="dropdown-toggle p-3" href="#" id="dropdownMenuLink"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item edit_button"
                                       href="{{route('admin.sellPost.offer',[$row->sell_post_id])}}">
                                        <i class="fas fa-comments text-info pr-2" aria-hidden="true"></i>
                                        @lang('Conversation')
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="100%">@lang('No Data Found')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>
    </div>
@endsection

@push('js')
    <script>
    </script>
@endpush
