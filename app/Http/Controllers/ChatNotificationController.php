<?php

namespace App\Http\Controllers;

use App\Events\UpdateOfferChatNotification;
use App\Models\Admin;
use App\Models\SellPostChat;
use App\Models\SellPostOffer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class ChatNotificationController extends Controller
{

    public function show(Request $request, $uuid)
    {
        $offerRequest = SellPostOffer::where('uuid', $uuid)
            ->firstOrFail();
        $siteNotifications = SellPostChat::whereHasMorph(
            'chatable',
            [
                User::class,
                Admin::class,
            ],
            function ($query) use ($offerRequest) {
                $query->where([
                    'offer_id' => $offerRequest->id,
                    'sell_post_id' => $offerRequest->sell_post_id
                ]);
            }
        )->with('chatable:id,username,phone,image')->get();

        return $siteNotifications;
    }

    public function newMessage(Request $request)
    {
        $rules = [
            'offer_id' => ['required'],
            'sell_post_id' => ['required'],
            'message' => ['required']
        ];

        $req = Purify::clean($request->all());
        $validator = Validator::make($req, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->messages()], 200);
        }
        $user = Auth::user();

        $sellPostOffer = SellPostOffer::where('id', $request->offer_id)
            ->where('sell_post_id', $request->sell_post_id)
            ->firstOrFail();


        $chat = new SellPostChat();
        $chat->description = $req['message'];
        $chat->sell_post_id = $sellPostOffer->sell_post_id;
        $chat->offer_id = $sellPostOffer->id;
        $log = $user->chatable()->save($chat);


        $data['id'] = $log->id;
        $data['chatable_id'] = $log->chatable_id;
        $data['chatable_type'] = $log->chatable_type;
        $data['chatable'] = [
            'fullname' => $log->chatable->fullname,
            'id' => $log->chatable->id,
            'image' => $log->chatable->image,
            'mobile' => $log->chatable->mobile,
            'imgPath' => $log->chatable->imgPath,
            'username' => $log->chatable->username,
        ];
        $data['description'] = $log->description;
        $data['is_read'] = $log->is_read;
        $data['is_read_admin'] = $log->is_read_admin;
        $data['formatted_date'] = $log->formatted_date;
        $data['created_at'] = $log->created_at;
        $data['updated_at'] = $log->updated_at;

        event(new \App\Events\OfferChatNotification($data, $sellPostOffer->uuid));

        return response(['success' => true], 200);
    }


    public function showByAdmin($uuid)
    {
        $offerRequest = SellPostOffer::where('uuid', $uuid)
            ->firstOrFail();


        $siteNotifications = SellPostChat::whereHasMorph(
            'chatable',
            [
                User::class,
                Admin::class
            ],
            function ($query) use ($offerRequest) {
                $query->where([
                    'offer_id' => $offerRequest->id,
                    'sell_post_id' => $offerRequest->sell_post_id
                ]);
            }
        )->with('chatable:id,username,image')->get();

        return $siteNotifications;
    }


    public function newMessageByAdmin(Request $request)
    {

        $rules = [
            'offer_id' => ['required'],
            'sell_post_id' => ['required'],
            'message' => ['required']
        ];

        $req = Purify::clean($request->all());
        $validator = Validator::make($req, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->messages()], 200);
        }


        $user = auth::guard('admin')->user();
        $sellPostOffer = SellPostOffer::where('id', $request->offer_id)
            ->where('sell_post_id', $request->sell_post_id)
            ->firstOrFail();

        $chat = new SellPostChat();
        $chat->description = $req['message'];
        $chat->offer_id = $sellPostOffer->id;
        $chat->sell_post_id = $sellPostOffer->sell_post_id;
        $log = $user->chatable()->save($chat);


        $uuid = $sellPostOffer->uuid;
        $data['id'] = $log->id;
        $data['chatable_id'] = $log->chatable_id;
        $data['chatable_type'] = $log->chatable_type;
        $data['chatable'] = [
            'fullname' => $log->chatable->fullname,
            'id' => $log->chatable->id,
            'image' => $log->chatable->image,
            'mobile' => $log->chatable->mobile,
            'imgPath' => $log->chatable->imgPath,
            'username' => $log->chatable->username,
        ];
        $data['description'] = $log->description;
        $data['is_read'] = $log->is_read;
        $data['is_read_admin'] = $log->is_read_admin;
        $data['formatted_date'] = $log->formatted_date;
        $data['created_at'] = $log->created_at;
        $data['updated_at'] = $log->updated_at;

        event(new \App\Events\OfferChatNotification($data, $uuid));
        return response(['success' => true], 200);
    }

    public function readAt($id)
    {
        $siteNotification = SellPostChat::find($id);
        if ($siteNotification) {
            $siteNotification->delete();
            if (Auth::guard('admin')->check()) {
                event(new UpdateOfferChatNotification(Auth::guard('admin')->id()));
            } else {
                event(new UpdateOfferChatNotification(Auth::id()));
            }
            $data['status'] = true;
        } else {
            $data['status'] = false;
        }
        return $data;
    }

    public function readAllByAdmin()
    {
        $siteNotification = SellPostChat::whereHasMorph(
            'chatable',
            [Admin::class],
            function ($query) {
                $query->where([
                    'chatable_id' => Auth::guard('admin')->id()
                ]);
            }
        )->delete();

        if ($siteNotification) {
            event(new UpdateOfferChatNotification(Auth::guard('admin')->id()));
        }
        $data['status'] = true;
        return $data;
    }

    public function readAll()
    {
        $siteNotification = SellPostChat::whereHasMorph(
            'chatable',
            [User::class],
            function ($query) {
                $query->where([
                    'chatable' => Auth::id()
                ]);
            }
        )->delete();

        if ($siteNotification) {
            event(new UpdateOfferChatNotification(Auth::id()));
        }

        $data['status'] = true;
        return $data;
    }
}
