@extends($theme.'layouts.user')
@section('title',__($page_title))

@section('content')

        <div class="container login-section">
            <div class="row justify-content-center bg-gradient">
                <div class="col-md-12">
                    <div class="contact-box mt-3 mx-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-3">@lang($page_title)</h4>

                            <a href="{{route('user.ticket.create')}}" class="btn btn-success btn-sm"> <i class="fa fa-plus-circle"></i> @lang('Create Ticket')</a>
                        </div>

                            <div class="table-responsive">
                                <table class="table table-bordered transection__table mt-5" id="service-table">
                                    <thead class="">
                                    <tr>
                                        <th scope="col">@lang('Subject')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Last Reply')</th>
                                        <th scope="col">@lang('Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($tickets as $key => $ticket)
                                        <tr>
                                            <td data-label="@lang('Subject')">
                                                    <span
                                                        class="font-weight-bold"> [{{ trans('Ticket#').$ticket->ticket }}
                                                        ] {{ $ticket->subject }} </span>
                                            </td>
                                            <td data-label="@lang('Status')">
                                                @if($ticket->status == 0)
                                                    <span
                                                        class="badge rounded-pill bg-success">@lang('Open')</span>
                                                @elseif($ticket->status == 1)
                                                    <span
                                                        class="badge rounded-pill bg-primary">@lang('Answered')</span>
                                                @elseif($ticket->status == 2)
                                                    <span
                                                        class="badge rounded-pill bg-warning">@lang('Replied')</span>
                                                @elseif($ticket->status == 3)
                                                    <span class="badge rounded-pill bg-dark">@lang('Closed')</span>
                                                @endif
                                            </td>

                                            <td data-label="@lang('Last Reply')">
                                                {{diffForHumans($ticket->last_reply) }}
                                            </td>

                                            <td data-label="@lang('Action')">
                                                <a href="{{ route('user.ticket.view', $ticket->ticket) }}"
                                                   class="btn btn-sm btn-custom"
                                                   data-toggle="tooltip" title="" data-original-title="Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="100%">{{__('No Data Found!')}}</td>
                                        </tr>

                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{ $tickets->appends($_GET)->links($theme.'partials.pagination') }}

                    </div>

                </div>

            </div>
        </div>

@endsection
