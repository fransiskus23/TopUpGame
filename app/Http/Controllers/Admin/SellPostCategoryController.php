<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SellPostTrait;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\SellPost;
use App\Models\Language;
use App\Models\SellPostCategory;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\SellPostCategoryDetail;
use App\Models\SellPostChat;
use App\Models\SellPostOffer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SellPostCategoryController extends Controller
{
    use Upload, Notify, SellPostTrait;

    public function category()
    {
        $manageCategory = SellPostCategory::with(['details'])->withCount('activePost')->latest()->get();

        return view('admin.sellPostCategory.categoryList', compact('manageCategory'));
    }

    public function categoryCreate()
    {
        $languages = Language::all();
        return view('admin.sellPostCategory.categoryCreate', compact('languages'));
    }

    public function categoryStore(Request $request, $language)
    {

        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));
        DB::beginTransaction();
        try {
            if ($request->has('image')) {
                $purifiedData['image'] = $request->image;
            }

            $rules = [
                'name.*' => 'required|max:40',
                'sell_charge' => 'sometimes|required|numeric|min:1',
                'image' => 'required|mimes:jpg,jpeg,png',
            ];
            $message = [
                'name.*.required' => 'Name field is required',
                'name.*.max' => 'This field may not be greater than :max characters',
                'image.required' => 'Image is required',
                'sell_charge.required' => 'Sell Charge  is required',
            ];

            $validate = Validator::make($purifiedData, $rules, $message);

            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }

            $category = new SellPostCategory();

            $input_form = [];
            if ($request->has('field_name')) {
                for ($a = 0; $a < count($request->field_name); $a++) {
                    $arr = array();
                    $arr['field_name'] = clean($request->field_name[$a]);
                    $arr['field_level'] = $request->field_name[$a];
                    $arr['type'] = $request->type[$a];
                    $arr['validation'] = $request->validation[$a];
                    $input_form[$arr['field_name']] = $arr;
                }
            }

            $input_post = [];
            if ($request->has('field_specification')) {
                for ($a = 0; $a < count($request->field_specification); $a++) {
                    $arr = array();
                    $arr['field_name'] = clean($request->field_specification[$a]);
                    $arr['field_level'] = $request->field_specification[$a];
                    $arr['type'] = $request->type[$a];
                    $arr['validation'] = $request->validation_specification[$a];
                    $input_post[$arr['field_name']] = $arr;
                }
            }

            if (isset($purifiedData['field_name'])) {
                $category->form_field = $input_form;
            }

            if (isset($purifiedData['field_specification'])) {
                $category->post_specification_form = $input_post;
            }

            if($request->has('status')){
                $category->status = $request->status;
            }

            if ($request->has('sell_charge')) {
                $category->sell_charge = $request->sell_charge;
            }


            if ($request->hasFile('image')) {
                try {
                    $category->image = $this->uploadImage($purifiedData['image'], config('location.sellPostCategory.path'), config('location.sellPostCategory.size'));
                } catch (\Exception $exp) {
                    return back()->with('error', 'Image could not be uploaded.');
                }
            }

            $category->save();

            $category->details()->create([
                'language_id' => $language,
                'name' => $purifiedData["name"][$language],
            ]);

            DB::commit();

            return back()->with('success', 'Category Successfully Saved');
        } catch (\Exception$e) {
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    public function categoryEdit($id)
    {
        $languages = Language::all();
        $categoryDetails = SellPostCategoryDetail::with('sellPostCategory')->where('sell_post_category_id', $id)->get()->groupBy('language_id');
        return view('admin.sellPostCategory.categoryEdit', compact('languages', 'categoryDetails', 'id'));
    }


    public function categoryUpdate(Request $request, $id, $language_id)
    {
        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));
        DB::beginTransaction();

        try {
            if ($request->has('image')) {
                $purifiedData['image'] = $request->image;
            }

            $rules = [
                'name.*' => 'required|max:40',
                'sell_charge' => 'sometimes|required|numeric|min:1',
            ];
            $message = [
                'name.*.required' => 'Name field is required',
                'name.*.max' => 'This field may not be greater than :max characters',
                'sell_charge.required' => 'Sell Charge  is required',
            ];

            $validate = Validator::make($purifiedData, $rules, $message);

            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }

            $input_form = [];
            if ($request->has('field_name')) {
                for ($a = 0; $a < count($request->field_name); $a++) {
                    $arr = array();
                    $arr['field_name'] = clean($request->field_name[$a]);
                    $arr['field_level'] = $request->field_name[$a];
                    $arr['type'] = $request->type[$a];
                    $arr['validation'] = $request->validation[$a];
                    $input_form[$arr['field_name']] = $arr;
                }
            }

            $input_post = [];
            if ($request->has('field_specification')) {
                for ($a = 0; $a < count($request->field_specification); $a++) {
                    $arr = array();
                    $arr['field_name'] = clean($request->field_specification[$a]);
                    $arr['field_level'] = $request->field_specification[$a];
                    $arr['type'] = $request->type[$a];
                    $arr['validation'] = $request->validation_specification[$a];
                    $input_post[$arr['field_name']] = $arr;
                }
            }

            $category = SellPostCategory::findOrFail($id);

            if ($request->hasFile('image')) {
                $category->image = $this->uploadImage($purifiedData['image'], config('location.sellPostCategory.path'), config('location.sellPostCategory.size'), $category->image);
            }

            if (isset($purifiedData['field_name'])) {
                $category->form_field = $input_form;
            }

            if (isset($purifiedData['field_specification'])) {
                $category->post_specification_form = $input_post;
            }

            if (isset($purifiedData['sell_charge'])) {
                $category->sell_charge = $request->sell_charge;
            }


            if($request->has('status')){
                $category->status = $request->status;
            }
            $category->save();

            $category->details()->updateOrCreate([
                'language_id' => $language_id
            ],
                [
                    'name' => $purifiedData["name"][$language_id],
                ]
            );
            DB::commit();

            return back()->with('success', 'Category Successfully Updated');

        } catch (\Exception$e) {
            DB::rollBack();
           return back()->with('error',$e->getMessage());
        }

    }

    public function activeGameMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Category.');
            return response()->json(['error' => 1]);
        } else {
            SellPostCategory::whereIn('id', $request->strIds)->update([
                'status' => 1,
            ]);
            session()->flash('success', 'Status has been active');
            return response()->json(['success' => 1]);
        }
    }

    public function inactiveGameMultiple(Request $request)
    {

        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Category.');
            return response()->json(['error' => 1]);
        } else {
            SellPostCategory::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);
            session()->flash('success', 'Status has been deactive');
            return response()->json(['success' => 1]);

        }
    }

    public function categoryDelete($id)
    {
        $categoryData = SellPostCategory::findOrFail($id);
        if(0 < $categoryData->post->count()){
            session()->flash('warning','This Category has a lot of post');
            return back();
        }

        $old_image = $categoryData->image;
        $location = config('location.sellPostCategory.path');

        if (!empty($old_image)) {
            @unlink($location . '/' . $old_image);
        }

        $categoryData->delete();
        return back()->with('success', 'Category has been deleted');
    }

    public function sellList($status=null,$user_id=null)
    {
        if($user_id != null){
            $data['sellPost']= SellPost::where('user_id',$user_id)->orderBy('id','desc')->get();
        }else{
            $value = $this->getValueByStatus($status);
            abort_if(!isset($value), 404);

            $data['sellPost'] = SellPost::status($value)
                ->orderBy('id', 'desc')->paginate(config('basic.paginate'));
        }

        return view('admin.sellPostList.index', $data);

    }

    public function sellDetails($id)
    {
        $data['activity'] = ActivityLog::whereSell_post_id($id)->with('activityable:id,username,image')->orderBy('id', 'desc')->get();

        $data['category'] = SellPostCategory::with('details')->whereStatus(1)->get();
        $data['sellPost'] = SellPost::findOrFail($id);

        return view('admin.sellPostList.edit', $data);
    }

    public function SellUpdate(Request $request, $id)
    {
        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));

        try {
            if ($request->has('image')) {
                $purifiedData['image'] = $request->image;
            }

            $rules = [
                'title' => 'required|max:40',
                'price' => 'required',
                'details' => 'required',
                'image' => 'required',
            ];
            $message = [
                'title.required' => 'Title field is required',
                'price.required' => 'Price field is required',
                'details.required' => 'Details field is required',
                'image' => 'Image field is required',
            ];

            $validate = Validator::make($purifiedData, $rules, $message);

            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }


            $gameSell = SellPost::findOrFail($id);
            $gameSell->category_id = $request->category;

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
                                            'field_value' => $v,
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


            $images = array();
            if ($request->hasFile('image')) {
                try {
                    foreach ($gameSell->image as $image) {
                        $images[] = $image;
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

            if (isset($purifiedData['status'])) {
                $gameSell->status = isset($purifiedData['status']) ? 1 : 0;
            }


            $gameSell->save();


            return back()->with('success', 'Successfully Updated');
        } catch (\Exception$e) {

            return back();
        }
    }

    public function sellSearch(Request $request)
    {

        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $sellPost = SellPost::with('user')->orderBy('id', 'DESC')
            ->when(isset($search['title']), function ($query) use ($search) {
                return $query->where('title', 'LIKE', "%{$search['title']}%");
            })
            ->when(isset($search['user_name']), function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['user_name']}%")
                        ->orWhere('username', 'LIKE', "%{$search['user_name']}%");
                });
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $sellPost = $sellPost->appends($search);
        return view('admin.sellPostList.index', compact('sellPost'));
    }

    public function sellAction(Request $request)
    {
        DB::beginTransaction();
        try {


            $gameSell = SellPost::findOrFail($request->sell_post_id);
            $gameSell->status = $request->status;
            $gameSell->save();


            $title = $gameSell->activityTitle;
            $admin = Auth::user();

            $activity = new ActivityLog();
            $activity->title = $title;
            $activity->sell_post_id = $request->sell_post_id;
            $activity->description = $request->comments;

            $admin->activities()->save($activity);
            DB::commit();

            $user = $gameSell->user;
            $msg = [
                'title' => $gameSell->title,
                'status' => $title,
                'comments' => $request->comments

            ];
            $action = [
                "link" => route('sellPost.details', [@$gameSell->title, $request->sell_post_id]),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->userPushNotification($user, 'SELL_APPROVE', $msg, $action);

            $this->sendMailSms($user, 'SELL_APPROVE', [
                'title' => $gameSell->title,
                'status' => $title,
                'short_comment' => $request->comments
            ]);

            session()->flash('success', 'Update Successfully');
            return back();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }

    }

    public function SellDelete($id, $imgDelete)
    {
        $images = [];
        $galleryImage = SellPost::findOrFail($id);
        $old_images = $galleryImage->image;
        $location = config('location.gameSell.path');

        if (!empty($old_images)) {
            foreach ($old_images as $file) {
                if ($file == $imgDelete) {
                    @unlink($location . '/' . $file);
                    @unlink($location . '/thumb_' . $file);
                } elseif ($file != $imgDelete) {
                    $images[] = $file;
                }
            }
        }
        $galleryImage->image = $images;
        $galleryImage->save();
        return back()->with('success', 'Image has been deleted');
    }

    public function sellPostOffer($sellPostId)
    {

        $sellPostOffer = SellPostOffer::with(['user', 'lastMessage'])->whereSell_post_id($sellPostId)
            ->get()
            ->sortByDesc('lastMessage.created_at');


        $offer = null;
        if (0 < count($sellPostOffer)) {
            $offer = $sellPostOffer->first();

            if (!$offer->uuid) {
                $offer->uuid = Str::uuid();
                $offer->save();
            }

            return redirect()->route('admin.sellPost.conversation', $offer->uuid);
        } else {
            $offer = null;
        }


        $data['sellPostOffer'] = $sellPostOffer;
        $data['offer'] = $offer;
        return view('admin.sellPostList.offerList', $data);
    }

    public function conversation($uuid)
    {

        $offer = SellPostOffer::with(['user', 'lastMessage'])->where('uuid', $uuid)
            ->firstOrFail();


        $data['sellPostOffer'] = SellPostOffer::with(['user', 'lastMessage'])->whereSell_post_id($offer->sell_post_id)
            ->get()
            ->sortByDesc('lastMessage.created_at');

        $data['persons'] = SellPostChat::where([
            'offer_id' => $offer->id,
            'sell_post_id' => $offer->sell_post_id
        ])
            ->with('chatable')
            ->get()->pluck('chatable')->unique('chatable');

        $data['offer'] = $offer;

        return view('admin.sellPostList.offerList', $data);
    }

    public function sellOffer($userId)
    {
        $data['sellPostOffer']=SellPostOffer::with('sellPost')->where('user_id',$userId)->get();
        return view('admin.sellPostList.offer.list', $data);
    }
}
