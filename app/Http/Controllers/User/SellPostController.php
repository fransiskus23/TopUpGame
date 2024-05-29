<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\ActivityLog;
use App\Models\SellPost;
use App\Models\SellPostCategory;
use App\Models\SellPostChat;
use App\Models\SellPostOffer;
use App\Models\SellPostPayment;
use App\Models\User;
use App\Models\Gateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Facades\App\Services\BasicService;

class SellPostController extends Controller
{
    use Upload, Notify;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function sellPostOrder()
    {
        $user=$this->user;
        $data['sellPostOrders']=SellPostPayment::with('sellPost')->whereUser_id($user->id)->wherePayment_status(1)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));

        return view($this->theme . 'user.gameSell.orderList', $data);
    }

    public function sellPostOrderSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $sellPostOrders = SellPostPayment::where('user_id', $this->user->id)->with('user')
            ->when(@$search['transaction_id'], function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $sellPostOrdersOrders = $sellPostOrders->appends($search);


        return view($this->theme . 'user.gameSell.orderList', compact('sellPostOrders'));

    }

    public function sellPostOfferList(){
        $user=$this->user;
        $data['sellPostOffer'] = SellPostOffer::with('sellPost')->whereUser_id($user->id)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));


        return view($this->theme . 'user.gameSell.offerList', $data);
    }

    public function sellCreate(Request $request)
    {

        $data['categoryList'] = SellPostCategory::with('details')
        ->whereStatus(1)
            ->get();

        if ($request->has('category')) {
            $data['category'] = SellPostCategory::with('details')->whereStatus(1)->findOrFail($request->category);
            return view($this->theme . 'user.gameSell.create', $data);
        }
        return view($this->theme . 'user.gameSell.create', $data);
    }

    public function sellStore(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'price' => 'required|numeric|min:1',
            'title' => 'required',
            'comments' => 'required',
            'image' => 'required'
        ]);
        $category = SellPostCategory::whereStatus(1)->findOrFail($request->category);


        $rules = [];
        $inputField = [];
        if ($category->form_field != null) {
            foreach ($category->form_field as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }

        $rulesSpecification = [];
        $inputFieldSpecification = [];
        if ($category->post_specification_form != null) {
            foreach ($category->post_specification_form as $key => $cus) {
                $rulesSpecification[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rulesSpecification[$key], 'image');
                    array_push($rulesSpecification[$key], 'mimes:jpeg,jpg,png');
                    array_push($rulesSpecification[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rulesSpecification[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rulesSpecification[$key], 'max:300');
                }
                $inputFieldSpecification[] = $key;
            }
        }

        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));

        $newRules =array_merge($rules, $rulesSpecification);


        $validate = Validator::make($purifiedData, $newRules);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        if ($request->has('image')) {
            $purifiedData['image'] = $request->image;
        }



        $gameSell = new SellPost();
        $gameSell->user_id = Auth()->user()->id;
        $gameSell->category_id = $purifiedData['category'];
        $gameSell->sell_charge=$category->sell_charge;


        $images = array();
        if ($request->hasFile('image')) {
            try {
                $gameImage = $purifiedData['image'];
                foreach ($gameImage as $file) {

                    $images[] = $this->uploadImage($file, config('location.sellingPost.path'), config('location.sellingPost.thumb'));
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
            $gameSell->image = $images;
        }



        if (isset($purifiedData['title'])) {
            $gameSell->title = $request->title;
        }
        if (isset($purifiedData['price'])) {
            $gameSell->price = $request->price;
        }

        if (isset($purifiedData['credential'])) {
            $gameSell->credential = $request->credential;
        }

        if (isset($purifiedData['details'])) {
            $gameSell->details = $request->details;
        }

        if (isset($purifiedData['comments'])) {
            $gameSell->comments = $request->comments;
        }

        if (isset($purifiedData['status'])) {
            $gameSell->status = isset($purifiedData['status']) ? 1 : 0;
        }


        $collection = collect($request);
        $reqField = [];
        if ($category->form_field != null) {
            foreach ($collection as $k => $v) {
                foreach ($category->form_field as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {

                                try {
                                    $image = $request->file($inKey);
                                    $location = config('location.sellingPost.path');
                                    $filename = $this->uploadImage($image, $location);;
                                    $reqField[$inKey] = [
                                        'field_name' => $inKey,
                                        'field_value' => $filename,
                                        'type' => $inVal->type,
                                        'validation' => $inVal->validation,
                                    ];

                                } catch (\Exception $exp) {
                                    return back()->with('error', 'Image could not be uploaded.')->withInput();
                                }

                            }
                        } else {
                            $reqField[$inKey] = [
                                'field_name' => $inKey,
                                'field_value' => $v,
                                'type' => $inVal->type,
                                'validation' => $inVal->validation,
                            ];
                        }
                    }
                }
            }
            $gameSell['credential'] = $reqField;
        } else {
            $gameSell['credential'] = null;
        }

        $collectionSpecification = collect($request);
        $reqFieldSpecification = [];
        if ($category->post_specification_form != null) {
            foreach ($collectionSpecification as $k => $v) {
                foreach ($category->post_specification_form as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {

                                try {
                                    $image = $request->file($inKey);
                                    $location = config('location.sellingPost.path');
                                    $filename = $this->uploadImage($image, $location);;
                                    $reqField[$inKey] = [
                                        'field_name' => $inKey,
                                        'field_value' => $filename,
                                        'type' => $inVal->type,
                                        'validation' => $inVal->validation,
                                    ];

                                } catch (\Exception $exp) {
                                    return back()->with('error', 'Image could not be uploaded.')->withInput();
                                }

                            }
                        } else {
                            $reqFieldSpecification[$inKey] = [
                                'field_name' => $inKey,
                                'field_value' => $v,
                                'type' => $inVal->type,
                                'validation' => $inVal->validation,
                            ];
                        }
                    }
                }
            }
            $gameSell['post_specification_form'] = $reqFieldSpecification;
        } else {
            $gameSell['post_specification_form'] = null;
        }

        $gameSell->save();
        return back()->with('success', 'Game Successfully Saved');
    }

    public function sellList()
    {
        $data['sellPost'] = SellPost::whereUser_id(Auth()->user()->id)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.gameSell.list', $data);
    }

    public function sellPostSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $sellPost = SellPost::where('user_id', $this->user->id)->with('user')
            ->when(@$search['title'], function ($query) use ($search) {
                return $query->where('title', 'LIKE', "%{$search['title']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->orderBy('id','desc')
            ->paginate(config('basic.paginate'));
        $sellPost = $sellPost->appends($search);


        return view($this->theme . 'user.gameSell.list', compact('sellPost'));

    }

    public function sellPostEdit($id)
    {
        $data['sellPost'] = SellPost::findOrFail($id);
        if($data['sellPost']->user_id != $this->user->id){
            abort(404);
        }

        return view($this->theme . 'user.gameSell.edit', $data);
    }

    public function sellPostUpdate(Request $request, $id)
    {

        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));
        if ($request->has('image')) {
            $purifiedData['image'] = $request->image;
        }

        $rules = [
            'title' => 'required|max:40',
            'price' => 'required|numeric|min:1',
            'details' => 'required',
            'comments' => 'required',
            'image' => 'sometimes|required'
        ];
        $message = [
            'name.required' => 'Name field is required',
            'price.required' => 'Price field is required',
            'details.required' => 'Details field is required',
            'comments.required' => 'Details field is required',
            'image.required' => 'Image field is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        DB::beginTransaction();
        try {

            $gameSell = SellPost::findOrFail($id);
            $gameSell->user_id = Auth()->user()->id;

            $category = SellPostCategory::whereStatus(1)->findOrFail($gameSell->category_id);
            $rules = [];
            $inputField = [];
            if ($category->form_field != null) {
                foreach ($category->form_field as $key => $cus) {
                    $rules[$key] = [$cus->validation];
                    if ($cus->type == 'file') {
                        array_push($rules[$key], 'image');
                        array_push($rules[$key], 'mimes:jpeg,jpg,png');
                        array_push($rules[$key], 'max:2048');
                    }
                    if ($cus->type == 'text') {
                        array_push($rules[$key], 'max:191');
                    }
                    if ($cus->type == 'textarea') {
                        array_push($rules[$key], 'max:300');
                    }
                    $inputField[] = $key;
                }
            }

            $rulesSpecification = [];
            $inputFieldSpecification = [];
            if ($category->post_specification_form != null) {
                foreach ($category->post_specification_form as $key => $cus) {
                    $rulesSpecification[$key] = [$cus->validation];
                    if ($cus->type == 'file') {
                        array_push($rulesSpecification[$key], 'image');
                        array_push($rulesSpecification[$key], 'mimes:jpeg,jpg,png');
                        array_push($rulesSpecification[$key], 'max:2048');
                    }
                    if ($cus->type == 'text') {
                        array_push($rulesSpecification[$key], 'max:191');
                    }
                    if ($cus->type == 'textarea') {
                        array_push($rulesSpecification[$key], 'max:300');
                    }
                    $inputFieldSpecification[] = $key;
                }
            }


            $newRules =array_merge($rules, $rulesSpecification);


            $validate = Validator::make($purifiedData, $newRules);

            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }

            $collection = collect($request);
            $reqField = [];
            $credentialChanges = '';
            if ($category->form_field != null) {
                foreach ($collection as $k => $v) {
                    foreach ($category->form_field as $inKey => $inVal) {
                        if ($k != $inKey) {
                            continue;
                        } else {
                            if ($inVal->type == 'file') {
                                if ($request->hasFile($inKey)) {
                                    try {
                                        $image = $request->file($inKey);
                                        $location = config('location.sellingPost.path');
                                        $filename = $this->uploadImage($image, $location);;
                                        $reqField[$inKey] = [
                                            'field_name' => $inKey,
                                            'field_value' => $filename,
                                            'type' => $inVal->type,
                                            'validation' => $inVal->validation,
                                        ];

                                    } catch (\Exception $exp) {
                                        return back()->with('error', 'Image could not be uploaded.')->withInput();
                                    }

                                }
                            } else {
                                $reqField[$inKey] = [
                                    'field_name' => $inKey,
                                    'field_value' => $v,
                                    'type' => $inVal->type,
                                    'validation' => $inVal->validation,
                                ];
                            }
                            if ($gameSell->credential->$inKey->field_value != $v) {
                                $credentialChanges .= "$inKey : " . $v . "<br>";
                            }
                        }
                    }
                }
                if (0 < strlen($credentialChanges)) {
                    $credentialChanges = "Changes Credentials <br>" . $credentialChanges;
                }


                $gameSell['credential'] = $reqField;
            } else {
                $gameSell['credential'] = null;
            }

            $collectionSpecification = collect($request);
            $reqFieldSpecification = [];
            $specificationChanges = '';
            if ($category->post_specification_form != null) {
                foreach ($collectionSpecification as $k => $v) {
                    foreach ($category->post_specification_form as $inKey => $inVal) {
                        if ($k != $inKey) {
                            continue;
                        } else {
                            if ($inVal->type == 'file') {
                                if ($request->hasFile($inKey)) {

                                    try {
                                        $image = $request->file($inKey);
                                        $location = config('location.sellingPost.path');
                                        $filename = $this->uploadImage($image, $location);;
                                        $reqField[$inKey] = [
                                            'field_name' => $inKey,
                                            'field_value' => $filename,
                                            'type' => $inVal->type,
                                            'validation' => $inVal->validation,
                                        ];

                                    } catch (\Exception $exp) {
                                        return back()->with('error', 'Image could not be uploaded.')->withInput();
                                    }

                                }
                            } else {
                                $reqFieldSpecification[$inKey] = [
                                    'field_name' => $inKey,
                                    'field_value' => $v,
                                    'type' => $inVal->type,
                                    'validation' => $inVal->validation,
                                ];
                                if ($gameSell->post_specification_form->$inKey->field_value != $v) {
                                    $specificationChanges .= "$inKey : " . $v . "<br>";
                                }
                            }
                        }
                    }
                }
                if (0 < strlen($specificationChanges)) {
                    $specificationChanges = "Changes Specification <br>" . $specificationChanges;
                }
                $gameSell['post_specification_form'] = $reqFieldSpecification;
            } else {
                $gameSell['post_specification_form'] = null;
            }

            $changeImage = '';
            $images = array();

            if ($request->hasFile('image')) {

                if ($gameSell->image != $request->image) {
                    $changeImage = ' Image has been updated ' . "<br>";
                }

              try {
                if($gameSell->image){
                    foreach ($gameSell->image as $image) {
                        $images[] = $image;
                    }
                }
                    $gameImage = $purifiedData['image'];
                    foreach ($gameImage as $file) {
                        $images[] = $this->uploadImage($file, config('location.sellingPost.path'), config('location.sellingPost.thumb'));
                    }
                } catch (\Exception $exp) {
                    return back()->with('error', 'Image could not be uploaded.');
                }
                $gameSell->image = $images;
            }


            $changesTitle = '';
            if (isset($purifiedData['title'])) {
                if ($gameSell->title != $request->title) {
                    $changesTitle = 'Title ' . $gameSell->title . ' updated to ' . $request->title . "<br>";
                }
                $gameSell->title = $request->title;

            }

            $changesPrice = '';
            if (isset($purifiedData['price'])) {
                if ($gameSell->price != $request->price) {
                    $changesPrice = 'Price ' . $gameSell->price . ' updated to ' . $request->price . "<br>";
                }
                $gameSell->price = $request->price;
            }


            $changesDetails = '';
            if (isset($purifiedData['details'])) {
                if ($gameSell->details != $request->details) {
                    $changesDetails = "Details has been Updated <br>";
                }
                $gameSell->details = $request->details;
            }

            if (isset($purifiedData['comments'])) {
                $gameSell->comments = $request->comments;
            }

            $gameSell->status = 2;
            $gameSell->save();


            $user = Auth::user();

            if ($changesTitle . $changesPrice . $credentialChanges . $specificationChanges . $changesDetails . $changeImage != '') {
                $activity = new ActivityLog();
                $activity->sell_post_id = $id;
                $activity->title = "Resubmission";
                $activity->description = $changesTitle . $changesPrice . $changeImage . $credentialChanges . $specificationChanges . $changesDetails;
                $user->activities()->save($activity);
            }

            DB::commit();

            return back()->with('success', 'Successfully Updated');
        } catch (\Exception$e) {
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    public function sellPostDelete($id)
    {
        $sellPost = SellPost::findOrFail($id);
        $old_image = $sellPost->image;
        $location = config('location.gameSell.path');

        if (!empty($old_image)) {
            @unlink($location . '/' . $old_image);
        }

        $sellPost->delete();
        return back()->with('success', 'Successfully Deleted');
    }

    public function SellDelete($id, $imgDelete)
    {
        $images = [];
        $galleryImage = SellPost::findOrFail($id);
        $old_images = $galleryImage->image;
        $location = config('location.sellingPost.path');

        if (!empty($old_images)) {
            foreach ($old_images as $file) {
                if ($file == $imgDelete) {
                    @unlink($location . '/' . $file);
                } elseif ($file != $imgDelete) {
                    $images[] = $file;
                }
            }
        }
        $galleryImage->image = $images;
        $galleryImage->save();
        return back()->with('success', 'Image has been deleted');
    }

    public function sellPostOffer(Request $request)
    {

        $purifiedData = Purify::clean($request->except('_token', '_method'));

        $rules = [
            'amount' => 'required|numeric|min:1',
            'description' => 'required',
        ];
        $message = [
            'amount.required' => 'Amount field is required',
            'description.required' => 'Description field is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $auth = $this->user;
        $sellPost = SellPost::where('status', 1)->findOrFail($request->sell_post_id);

        if($sellPost->payment_status == 1){
            return back()->with('warning', 'You can not offer already someone purchases');
        }


        $sellPostOffer = SellPostOffer::whereUser_id($auth->id)->whereSell_post_id($sellPost->id)->count();

        if ($sellPostOffer > 0) {

            $sellPostOffer = SellPostOffer::whereUser_id($auth->id)->whereSell_post_id($request->sell_post_id)->update(["amount" => $request->amount,
                "description" => $request->description,
                "status" => 3
            ]);

        } else {
            SellPostOffer::create([
                'author_id' => $sellPost->user_id,
                'user_id' => Auth::user()->id,
                'sell_post_id' => $request->sell_post_id,
                'amount' => $request->amount,
                'description' => $request->description,
            ]);
        }


        $sellPost = SellPost::findOrFail($request->sell_post_id);
        $user = $sellPost->user;
        if ($sellPostOffer > 0) {
            $this->isReOffer($sellPost, $user, $request);
        } else {
            $msg = [
                'title' => $sellPost->title,
                'amount' => $request->amount . ' ' . config('basic.currency'),
                'offer_by' => $sellPost->user->firstname . ' ' . $sellPost->user->lastname
            ];
            $action = [
                "link" => route('sellPost.details', [@slug($sellPost->title), $request->sell_post_id]),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->userPushNotification($user, 'SELL_OFFER', $msg, $action);

            $this->sendMailSms($user, 'SELL_OFFER', [
                'link' => route('sellPost.details', [@slug($sellPost->title), $request->sell_post_id]),
                'title' => $sellPost->title,
                'amount' => $request->amount . ' ' . config('basic.currency'),
                'offer_by' => $sellPost->user->firstname . ' ' . $sellPost->user->lastname,
                'description' => $request->description
            ]);
        }

        return back()->with('success', 'Offer Send');
    }

    public function isReOffer($sellPost, $user, $request)
    {
        $msg = [
            'title' => $sellPost->title,
            'amount' => $request->amount . ' ' . config('basic.currency'),
            'offer_by' => $sellPost->user->firstname . ' ' . $sellPost->user->lastname
        ];
        $action = [
            "link" => route('sellPost.details', [@slug($sellPost->title), $request->sell_post_id]),
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $this->userPushNotification($user, 'SELL_RE_OFFER', $msg, $action);

        $this->sendMailSms($user, 'SELL_RE_OFFER', [
            'link' => route('sellPost.details', [@slug($sellPost->title), $request->sell_post_id]),
            'title' => $sellPost->title,
            'amount' => $request->amount . ' ' . config('basic.currency'),
            'offer_by' => $sellPost->user->firstname . ' ' . $sellPost->user->lastname,
            'description' => $request->description
        ]);

        return 0;
    }

    public function sellPostOfferMore(Request $request)
    {
        $data['sellPostAll'] = SellPost::whereUser_id(Auth::id())->whereStatus(1)->get();
        $dateTrx = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateTrx);

        $query = SellPostOffer::query();
        $data['sellPostOffer'] = $query->where('author_id', Auth::id())->whereHas('sellPost')->whereHas('user')->with('sellPost')
            ->when(request('postId', false), function ($q, $postId) {
                $q->where('sell_post_id', $postId);
            })
            ->when(request('remark', false), function ($q, $remark) {
                $q->where('description', 'like', "%$remark%")
                    ->orWhereHas('user', function ($subQuery) use ($remark) {
                        $subQuery->where('firstname', 'like', "%$remark%")->orWhere('lastname', 'like', "%$remark%");
                    });
            })
            ->when($date == 1, function ($query) use ($dateTrx) {
                return $query->whereDate("created_at", $dateTrx);
            })
            ->when(!request('sortBy', false), function ($query, $sortBy) {
                $query->orderBy('updated_at', 'desc');
            })
            ->when(request('sortBy', false), function ($query, $sortBy) {
                if ($sortBy == 'latest') {
                    $query->orderBy('updated_at', 'desc');
                }
//                Payment Processing
                if ($sortBy == 'processing') {
                    $query->whereHas('sellPost', function ($qq) {
                        $qq->where('payment_lock', 1)->where('payment_status', 0)->whereNotNull('lock_at')->whereDate('lock_at', '<=', Carbon::now()->subMinutes(config('basic.payment_expired')));
                    })
                        ->where('status', 1)->where('payment_status', 0)->orderBy('amount', 'desc');
                }
//                Payment Complete
                if ($sortBy == 'complete') {
                    $query->whereHas('sellPost', function ($qq) {
                        $qq->where('payment_lock', 1)->where('payment_status', 1);
                    })
                        ->where('status', 1)->where('payment_status', 1)->orderBy('amount', 'desc');
                }
                if ($sortBy == 'low_to_high') {
                    $query->orderBy('amount', 'asc');
                }
                if ($sortBy == 'high_to_low') {
                    $query->orderBy('amount', 'desc');
                }
                if ($sortBy == 'pending') {
                    $query->whereStatus(0)->orderBy('amount', 'desc');
                }

                if ($sortBy == 'rejected') {
                    $query->whereStatus(2)->orderBy('amount', 'desc');
                }
                if ($sortBy == 'resubmission') {
                    $query->whereStatus(3)->orderBy('amount', 'desc');
                }
            })->paginate(config('basic.paginate'));
        return view($this->theme . 'sell-post-offer', $data);
    }

    public function sellPostOfferRemove(Request $request)
    {

        $sellPostOffer = SellPostOffer::with('sellPost')->findOrFail($request->offer_id);
        if ($sellPostOffer) {
            $sellPostOffer->delete();
        }
        return back()->with('success', 'Remove Offer');
    }

    public function sellPostOfferReject(Request $request)
    {

        $sellPostOffer = SellPostOffer::findOrFail($request->offer_id);
        if ($sellPostOffer->status != 2) {
            $sellPostOffer->update([
                'status' => 2
            ]);

            $user = $sellPostOffer->user;
            $msg = [
                'title' => $sellPostOffer->sellPost->title,
                'amount' => $sellPostOffer->amount . ' ' . config('basic.currency'),
            ];
            $action = [
                "link" => route('sellPost.details', [@slug($sellPostOffer->sellPost->title), $sellPostOffer->sellPost->id]),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->userPushNotification($user, 'OFFER_REJECT', $msg, $action);

            $this->sendMailSms($user, 'OFFER_REJECT', [
                'link' => route('sellPost.details', [@slug($sellPostOffer->sellPost->title), $sellPostOffer->sellPost->id]),
                'title' => $sellPostOffer->sellPost->title,
                'amount' => $sellPostOffer->amount . ' ' . config('basic.currency'),
            ]);

            return back()->with('success', 'Reject Offer');
        }
        return back()->with('warning', 'Already Rejected');
    }

    public function sellPostOfferAccept(Request $request)
    {
        $purifiedData = Purify::clean($request->except('_token', '_method'));

        $rules = [
            'description' => 'required',
        ];
        $message = [
            'description.required' => 'Description field is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        DB::beginTransaction();
        try {

            $offerDetails = SellPostOffer::findOrFail($request->offer_id);
            if ($offerDetails->uuid != '') {
                return back()->with('warning', 'Offer Can not be accepted');
            }
            if ($offerDetails->sell_post_id) {
                if (!$offerDetails->uuid) {
                    $offerDetails->uuid = Str::uuid();
                }
                $offerDetails->status = 1;
                $offerDetails->attempt_at = Carbon::now();
                $offerDetails->save();

                $user = Auth::user();
                $sellPostChat = new SellPostChat();
                $sellPostChat->sell_post_id = $offerDetails->sell_post_id;
                $sellPostChat->offer_id = $request->offer_id;
                $sellPostChat->description = $request->description;
                $user->sellChats()->save($sellPostChat);

                DB::commit();

                $user = $offerDetails->user;
                $msg = [
                    'title' => $offerDetails->sellPost->title,
                    'amount' => $offerDetails->amount . ' ' . config('basic.currency'),
                ];
                $action = [
                    "link" => route('user.offerChat', $offerDetails->uuid),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userPushNotification($user, 'OFFER_ACCEPTED', $msg, $action);

                $this->sendMailSms($user, 'OFFER_ACCEPTED', [
                    'link' => route('user.offerChat', $offerDetails->uuid),
                    'title' => $offerDetails->sellPost->title,
                    'amount' => $offerDetails->amount . ' ' . config('basic.currency'),
                ]);

                return back()->with('success', 'Accepted Offer');
            }

        } catch (\Exception $e) {
            DB::rollBack();
           return back()->with('error',$e->getMessage());
        }
    }

    public function sellPostOfferChat($uuid)
    {
        $auth = Auth::user();
        $offerRequest = SellPostOffer::where('uuid', $uuid)
            ->whereHas('user')
            ->whereHas('author')
            ->where(function ($query) use ($auth) {
                $query->where('user_id', $auth->id)
                    ->orWhere('author_id', $auth->id);
            })
            ->with('sellPost')
            ->firstOrFail();

        if (Auth::check() && $offerRequest->author_id == Auth::id()) {
            $data['isAuthor'] = true;
        } else {
            $data['isAuthor'] = false;
        }


        $data['persons'] = SellPostChat::where([
            'offer_id' => $offerRequest->id,
            'sell_post_id' => $offerRequest->sell_post_id
        ])
            ->with('chatable')
            ->get()->pluck('chatable')->unique('chatable');
        $sellPost = SellPost::FindOrFail($offerRequest->sell_post_id);

        return view($this->theme . 'sell-post-offer-chat', $data, compact('offerRequest', 'sellPost'));
    }

    public function sellPostOfferPaymentLock(Request $request)
    {

        $purifiedData = Purify::clean($request->except('_token', '_method'));
        $rules = ['amount' => 'required|numeric|min:1',];
        $message = ['amount.required' => 'Amount field is required',];

        $validate = Validator::make($purifiedData, $rules, $message);
        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $sellPostOffer = SellPostOffer::with('sellPost')->findOrFail($request->offer_id);

        if ($sellPostOffer->sellPost->payment_uuid != '') {
            return back()->with('warning', 'Payment can not be lock');
        }

        if ($sellPostOffer->author_id != Auth::id()) {
            abort(403);
        }
        if ($sellPostOffer->sellPost->payment_lock == 1) {
            return back()->with('warning', 'Payment Allready Lock');
        }

        $sellPostOffer->amount = $request->amount;
        $sellPostOffer->save();
        $sellPost = $sellPostOffer->sellPost;
        $sellPost->payment_lock = 1;
        $sellPost->lock_for = $sellPostOffer->user_id;
        $sellPost->lock_at = Carbon::now();
        $sellPost->payment_uuid = str::uuid();
        $sellPost->save();

        $user = $sellPostOffer->user;

        $msg = [
            'title' => $sellPostOffer->sellPost->title,
            'amount' => $request->amount . ' ' . config('basic.currency'),
        ];
        $action = [
            "link" => route('user.sellPost.payment.url',$sellPostOffer->sellPost->payment_uuid),
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $this->userPushNotification($user, 'PAYMENT_LOCK', $msg, $action);

        $this->sendMailSms($user, 'PAYMENT_LOCK', [
            'link' => route('user.sellPost.payment.url',$sellPostOffer->sellPost->payment_uuid),
            'title' => $sellPostOffer->sellPost->title,
            'amount' => $request->amount . ' ' . config('basic.currency'),
        ]);

        return back()->with('success', 'Payment Lock');
    }

    public function sellPostPayment(Request $request, SellPost $sellPost)
    {
        $user = $this->user;
        if ($user->id == $sellPost->user_id) {
            return back()->with('warning', "Author can't buy");
        }

        if ($sellPost->payment_status == 1 || $sellPost->payment_status == 3) {
            return back()->with('warning', 'Someone Already purchase it');
        }
        if ($sellPost->payment_lock == 1 && $sellPost->lock_for != $user->id) {
            return back()->with('warning', 'Someone Booked For Payment');
        }

        if (!$sellPost->payment_uuid) {
            $sellPost->payment_uuid = Str::uuid();
            $sellPost->save();
        }
        return redirect()->route('user.sellPost.payment.url', $sellPost);
    }

    public function sellPostPaymentUrl(SellPost $sellPost)
    {
        $user = $this->user;
        if ($user->id == $sellPost->user_id) {

            session()->flash('warning', "Author can't buy");
            return redirect()->route('sellPost.details',[slug($sellPost->title??'sell-post'),$sellPost->id]);
        }

        if ($sellPost->payment_status == 1 || $sellPost->payment_status == 3) {
            session()->flash('warning', 'Someone Already Purchase It');
            return redirect()->route('sellPost.details',[slug($sellPost->title??'sell-post'),$sellPost->id]);
        }


        if ($sellPost->payment_lock == 1 && $sellPost->lock_for != $user->id) {
            session()->flash('warning', 'Someone Booked For Payment');
            return redirect()->route('sellPost.details',[slug($sellPost->title??'sell-post'),$sellPost->id]);
        }

        $price = $sellPost->price;
        $checkMyProposal = SellPostOffer::where([
            'user_id' => $user->id,
            'sell_post_id' => $sellPost->id,
            'status' => 1,
            'payment_status' => 0,
        ])->first();
        if ($checkMyProposal) {
            $price = (int)$checkMyProposal->amount;
        }
        $data['sellPost'] = $sellPost;
        $data['price'] = $price;
        $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by')->get();
        return view($this->theme . 'sell-post-payment', $data);
    }

    public function sellPostMakePayment(Request $request)
    {

        $this->validate($request, [
            'gateway' => ['required', 'numeric'],
            'sellPostId' => ['required', 'numeric']
        ], [
            'gateway.required' => 'Please select a payment method',
            'sellPostId.required' => 'Please select a sell post'
        ]);

        $sellPost = SellPost::where('id', $request->sellPostId)
            ->where('status', 1)
            ->first();

        if (!$sellPost) {
            return response()->json(['error' => 'This post already sold or not available to sell'], 422);
        }


        $gate = Gateway::where('status', 1)->findOrFail($request->gateway);


        $price = $sellPost->price;
        if (Auth::check()) {
            $user = Auth::user();
            $checkMyProposal = SellPostOffer::where([
                'user_id' => $user->id,
                'sell_post_id' => $sellPost->id,
                'status' => 1,
                'payment_status' => 0,
            ])->first();
            if ($checkMyProposal) {
                $price = (int)$checkMyProposal->amount;
            }
        }

        $discount = 0;


        $user = $this->user;

        $reqAmount = $price - $discount;
        $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
        $payable = getAmount($reqAmount + $charge);
        $final_amo = getAmount($payable * $gate->convention_rate);

        $admin_amount=$reqAmount * $sellPost->sell_charge/100;
        $seller_amount=$reqAmount-$admin_amount;

        $sellPostPayment = new SellPostPayment();
        $sellPostPayment->user_id = $user->id;
        $sellPostPayment->sell_post_id = $sellPost->id;
        $sellPostPayment->price = $reqAmount;
        $sellPostPayment->seller_amount= $seller_amount;
        $sellPostPayment->admin_amount=$admin_amount;
        $sellPostPayment->discount = $discount;
        $sellPostPayment->transaction = strRandom();

        $sellPostPayment->save();

        $fund = PaymentController::newFund($request, $user, $gate, $charge, $final_amo, $reqAmount);
        $sellPostPayment->fundable()->save($fund);
        session()->put('track', $fund['transaction']);


        return redirect()->route('user.addFund.confirm');
    }

    public function sellPostMyOffer()
    {
        $data['sellPostOffer'] = SellPostOffer::where('user_id',Auth::id())->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.gameSell.myOffer', $data);
    }

    public function myOfferSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $sellPostOffer = SellPostOffer::where('user_id', $this->user->id)->whereHas('sellPost')->with('user')
            ->when(@$search['title'], function ($query) use ($search) {
                $query->whereHas('sellPost', function ($qq) use ($search) {
                    $qq->where('title', 'like', "%{$search['title']}%");
                });
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->orderBy('id','desc')
            ->paginate(config('basic.paginate'));
        $sellPostOffer = $sellPostOffer->appends($search);

        return view($this->theme . 'user.gameSell.myOffer', compact('sellPostOffer'));

    }

}
