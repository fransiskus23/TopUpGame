@extends('admin.layouts.app')
@section('title')
    @lang('Offer List')
@endsection
@section('content')
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0">
        <div class="card-body">

            <div class="row no-gutters">
                <div class="col-lg-4 col-xl-4 border-right">
                    <div class="scrollable position-relative scroll-height">
                        <ul class="mailbox list-style-none">


                            @if(!empty($sellPostOffer))
                                @forelse($sellPostOffer as $item)
                                    <li>
                                        <div class="message-center">
                                            <a href="{{route('admin.sellPost.conversation',$item->uuid)}}"
                                               class="message-item d-flex align-items-center border-bottom px-3 py-2 {{(last(request()->segments()) == $item->uuid) ? 'sideNavTicket' : ''}}">
                                                <div class="user-img">
                                                    <img src="{{ $item->user->imgPath }}"
                                                         alt="user" class="img-fluid rounded-circle width-40p"> <span
                                                        class="profile-status online float-right"></span>
                                                </div>
                                                <div class="w-75 d-inline-block v-middle pl-2">
                                                    <h6 class="message-title mb-0 mt-1">{{ optional($item->user)->fullname }}</h6>
                                                    <span
                                                        class="font-12 text-nowrap d-block text-muted text-truncate">@lang(@$item->description)</span>
                                                    <span
                                                        class="font-12 text-nowrap d-block text-muted">{{dateTime($item->created_at)}}</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>

                                @empty
                                @endforelse
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8  col-xl-8">
                    @if(!empty($persons))
                        <div class="p-3 mb-4 shadow">
                            <div class="report  justify-content-center " id="pushChatArea">
                                <audio id="myAudio">
                                    <source src="{{asset('assets/admin/css/sound.mp3')}}" type="audio/mpeg">
                                </audio>

                                <div class="card ">
                                    <div
                                        class="adiv   justify-content-between align-items-center text-white p-2 d-flex">
                                        <p><i class="fas fa-users "></i> {{trans('Conversation')}}</p>

                                        <div class="d-flex user-chatlist">
                                            @if(!empty($persons))
                                                @forelse($persons as $person)
                                                    <div class="d-flex no-block align-items-center">
                                                        <a href="javascript:void(0)"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="{{$person->username}}"
                                                           class="mr-1 position-relative">

                                                            <i class="batti position-absolute fa fa-circle text-{{($person->lastSeen == true) ?'success':'warning' }} font-12"
                                                               title="{{($person->lastSeen == true) ?'Online':'Away' }}"></i>
                                                            <img src="{{$person->imgPath}}"
                                                                 alt="user" class="rounded-circle " width="30"
                                                                 height="30">
                                                        </a>

                                                    </div>
                                                @empty
                                                @endforelse
                                            @endif
                                        </div>
                                    </div>


                                    <div class="chat-length" ref="chatArea">
                                        <div v-for="(item, index) in items">
                                            <div
                                                v-if=" item.chatable_type == auth_model"
                                                class="d-flex flex-row justify-content-end p-3 "
                                                :title="item.chatable.username">
                                                <div
                                                    class="bg-white mr-2 pt-1 pb-4  pl-2 pr-2 position-relative mw-130">
                                                    <span class="text-wa">@{{item.description}}</span>
                                                    <span class="timmer">@{{item.formatted_date}}</span>

                                                </div>
                                                <img
                                                    :src="item.chatable.imgPath"
                                                    width="30" height="30">
                                            </div>

                                            <div v-else class="d-flex flex-row justify-content-start p-3"
                                                 :title="item.chatable.username">
                                                <img :src="item.chatable.imgPath" width="30" height="30">
                                                <div class="chat ml-2 pt-1 pb-4  pl-2 pr-5 position-relative mw-130">
                                                    @{{item.description}}
                                                    <span class="timmer">@{{item.formatted_date}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <form @submit.prevent="send" enctype="multipart/form-data" method="post">
                                        <div class="writing-box d-flex justify-content-between align-items-center">
                                            <div class="input--group form-group px-3 ">
                                                <input class="form--control type_msg" v-model.trim="message"
                                                       placeholder="{{trans('Type your message')}}"/>
                                            </div>
                                            <div class="send text-center">
                                                <button type="button" class="btn btn-success btn--success "
                                                        @click="send">
                                                    <i class="fas fa-paper-plane "></i>
                                                </button>
                                            </div>
                                        </div>

                                    </form>

                                </div>

                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

@endsection
@push('js')
    <script>
        'use strict';
        @if($offer)
        let pushChatArea = new Vue({
            el: "#pushChatArea",
            data: {
                items: [],
                auth_id: "{{auth()->guard('admin')->id()}}",
                auth_model: "App\\Models\\Admin",
                message: ''
            },
            beforeMount() {
                this.getNotifications();
                this.pushNewItem();
            },
            methods: {
                getNotifications() {
                    let app = this;
                    axios.get("{{ route('admin.push.chat.show',$offer->uuid) }}")
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

                    let channel = pusher.subscribe('offer-chat-notification.' + "{{ $offer->uuid }}");
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


                    axios.post("{{ route('admin.push.chat.newMessage')}}", {
                        offer_id: "{{$offer->id}}",
                        sell_post_id: "{{$offer->sell_post_id}}",
                        message: app.message
                    }).then(function (res) {

                        if (res.data.errors) {
                            var err = res.data.errors;
                            for (const property in err) {
                                Notiflix.Notify.Failure(`${err[property]}`);
                            }
                        }

                        if (res.data.success == true) {
                            app.message = '';
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

        @endif
    </script>

@endpush
