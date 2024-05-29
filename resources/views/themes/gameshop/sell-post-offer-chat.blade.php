@extends($theme . 'layouts.user')
@section('title', trans('Conversation'))

@section('content')
    <div class="sell-post-details">
        <div class="container">

            <div class="row g-4 g-md-5">
                <div class="col-lg-4">
                    <div class="game-box d-md-flex">
                        <div class="img-box d-none">
                            <img src="{{getFile(config('location.user.path'))}}" class="img-fluid" alt="...">
                        </div>
                        <div>
                            <h5 class="name">{{$sellPost->title}}</h5>
                            <div class="d-flex justify-content-between">
                                <span
                                    class="game-level">@lang('Price'): <span>{{config('basic.currency_symbol')}}{{$sellPost->price}}</span></span>

                                @if($sellPost->payment_lock == 1)
                                    @if($sellPost->payment_status==1)
                                        <span class="badge bg-success">@lang('Payment Completed')</span>
                                    @elseif($sellPost->lock_for == $sellPost->user_id && $sellPost->payment_status ==0 && \Carbon\Carbon::now() < Carbon\Carbon::parse($sellPost->lock_at)->addMinutes(config('basic.payment_expired')))
                                        @if(Auth::check() && Auth::id()==$sellPost->lock_for)
                                            <span class="badge bg-secondary">@lang('Waiting Payment')</span>
                                        @elseif(Auth::check() &&  Auth::id()==$sellPost->user_id)
                                            <span class="badge bg-warning text-dark">@lang('Payment Processing')</span>
                                        @else
                                            <span class="badge bg-warning text-dark">@lang('Going to Sell')</span>
                                        @endif
                                    @else
                                        <span
                                            class="badge bg-success custom-success text-light">@lang('Accepted')</span>
                                    @endif
                                @endif
                            </div>
                            <div class="row g-2 mt-3 more-info">
                                @forelse($sellPost->post_specification_form as $k => $v)
                                    <div class="col-6">
                                        <span>{{$v->field_name}}: {{$v->field_value}}</span>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="contact-box">
                        <div class="report  justify-content-center" id="pushChatArea">

                            <audio id="myAudio">
                                <source src="{{asset('assets/admin/css/sound.mp3')}}" type="audio/mpeg">
                            </audio>

                            <div class="card ">

                                <div
                                    class="adiv d-flex justify-content-between align-items-center text-white pt-1 padding">
                                    <p class="pt-2 ps-2"><i class="fas fa-users "></i> {{trans('Conversation')}}</p>
                                    <div class="d-flex justify-content-end">
                                        <div class="d-flex  user">
                                            @if(!empty($persons))
                                                @forelse($persons as $person)

                                                    <div title="admin"
                                                         class="d-flex flex-row justify-content-start me-1">
                                                        <a href="javascript:void(0)" title="{{'@'.$person->username}}"
                                                           class="mr-1 position-relative">
                                                            <i class="batti position-absolute fa fa-circle text-{{($person->lastSeen == true) ?trans('success'):trans('warning') }} font-12"
                                                               title="{{($person->lastSeen == true) ?trans('Online'):trans('Away') }}"></i>
                                                            <img src="{{$person->imgPath}}" width="30" height="30"></a>
                                                    </div>
                                                @empty
                                                @endforelse
                                            @endif

                                        </div>

                                        @if($isAuthor == true)
                                            <div class="btn-group btn-group-sm" role="group">
                                                <div class="btn-group" role="group">
                                                    <button id="offerActionBtn" type="button"
                                                            class="btn text-white dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="offerActionBtn">
                                                        <li><a class="dropdown-item paymentLock"
                                                               href="javascript:void(0)"
                                                               data-offer="{{$offerRequest->user->fullname}}"
                                                               data-resource="{{$offerRequest->id}}" data-
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#offerPaymentLock"><i
                                                                    class="text-success fa fa-check-circle"></i> @lang('Payment Lock')
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="chat-length" ref="chatArea">


                                    <div v-for="(item, index) in items">
                                        <div
                                            v-if="item.chatable_id == auth_id && item.chatable_type == auth_model"
                                            class="d-flex flex-row justify-content-end p-3 "
                                            :title="item.chatable.username">
                                            <div class="bg-white me-2 pt-1 pb-4  ps-2 pe-2 position-relative mw-130">
                                                <span class="text-wa">@{{item.description}}</span>
                                                <span class="timmer">@{{item.formatted_date}}</span>

                                            </div>
                                            <img
                                                :src="item.chatable.imgPath"
                                                width="30" height="30">
                                        </div>

                                        <div v-else="item.chatable_id != auth_id"
                                             class="d-flex flex-row justify-content-start p-3  "
                                             :title="item.chatable.username">
                                            <img
                                                :src="item.chatable.imgPath"
                                                width="30" height="30">
                                            <div class="chat ms-2 pt-1 pb-4  ps-2 pe-5 position-relative mw-130">
                                                @{{item.description}}
                                                <span class="timmer">
                                                @{{item.formatted_date}}</span>
                                            </div>
                                        </div>

                                    </div>

                                </div>


                                <form @submit.prevent="send" enctype="multipart/form-data" method="post" class="p-0">
                                    <div class="writing-box d-flex justify-content-between align-items-center">

                                        <div class="input-group px-3 mt-2">
                                            <input class="form-control type_msg" v-model.trim="message"
                                                   placeholder="{{trans('Type your message')}}"/>
                                        </div>
                                        <div class="send text-center">
                                            <button type="button" class="btn btn-success " @click="send"><i
                                                    class="fas fa-paper-plane "></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($isAuthor == true)
        <!-- Offer Payment Lock -->
        <div class="modal fade" id="offerPaymentLock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content ">
                    <div class="modal-header-custom modal-colored-header bg-custom">
                        <h4 class="modal-title" id="myModalLabel">@lang('Confirmation')</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">text-center
                        </button>
                    </div>
                    <form action="{{route('user.sellPostOfferPaymentLock')}}" method="POST">
                        @csrf
                        <div class="modal-body-custom">
                            <input type="hidden" class="sellPostPaymentLock" name="offer_id" value="">
                            <div class="mb-3">
                                <p>@lang('Are you sure to payment lock for') <span
                                        class="offerBy font-weight-bold"></span>?</p>
                            </div>

                            <div class="form-group">
                                <label>@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="text" name="amount" class="form-control earn" required="">
                                    <button
                                        class="btn btn-success-custom copy-btn">{{config('basic.currency')}}</button>
                                </div>
                                @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer-custom">

                            <button type="submit" class="btn btn-success-custom">@lang('Submit')
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')

    <script>
        'use strict';
        let pushChatArea = new Vue({
            el: "#pushChatArea",
            data: {
                items: [],
                auth_id: "{{auth()->id()}}",
                auth_model: "App\\Models\\User",
                message: ''
            },
            beforeMount() {
                this.getNotifications();
                this.pushNewItem();
            },
            methods: {
                getNotifications() {
                    let app = this;
                    axios.get("{{ route('user.push.chat.show',$offerRequest->uuid) }}")
                        .then(function (res) {
                            app.items = res.data;

                        })
                },

                pushNewItem() {
                    let app = this;
                    // Pusher.logToConsole = true;
                    let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                        encrypted: true,
                        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                    });

                    let channel = pusher.subscribe('offer-chat-notification.' + "{{ $offerRequest->uuid }}");
                    console.log(channel)

                    channel.bind('App\\Events\\OfferChatNotification', function (data) {
                        app.items.push(data.message);
                        var x = document.getElementById("myAudio");
                        x.play();
                        Vue.nextTick(() => {
                            let messageDisplay = app.$refs.chatArea
                            messageDisplay.scrollTop = messageDisplay.scrollHeight
                        })

                    });
                    channel.bind('App\\Events\\UpdateOfferChatNotification', function (data) {
                        app.getNotifications();

                        console.log('update')
                    });
                },

                send() {
                    let app = this;
                    if (app.message.length == 0) {
                        Notiflix.Notify.Failure(`{{trans('Type your message')}}`);
                        return 0;
                    }

                    axios.post("{{ route('user.push.chat.newMessage')}}", {
                        offer_id: "{{$offerRequest->id}}",
                        sell_post_id: "{{$offerRequest->sell_post_id}}",
                        message: app.message
                    }).then(function (res) {

                        if (res.data.errors) {
                            var err = res.data.errors;
                            for (const property in err) {
                                Notiflix.Notify.Failure(`${err[property]}`);
                            }
                            return 0;
                        }

                        app.message = '';

                        if (res.data.success == true) {
                            Vue.nextTick(() => {
                                let messageDisplay = app.$refs.chatArea
                                messageDisplay.scrollTop = messageDisplay.scrollHeight
                            })
                        }
                    }).catch(function (error) {

                    });

                }
            }
        });
    </script>


    <script>

        $(document).ready(function () {
            $('.paymentLock').on('click', function () {
                $('.sellPostPaymentLock').val($(this).data('resource'));
                $('.offerBy').text($(this).data('offer'));
            })
        })

    </script>

    @if($errors->any())
        <script>
            'use strict';
            @foreach ($errors->all() as $error)
            Notiflix.Notify.Failure(`{{trans($error)}}`);
            @endforeach
        </script>
    @endif
@endpush
