<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use App\Models\GiftCardDetail;
use App\Models\GiftCardCode;
use App\Models\GiftCardService;
use App\Models\Language;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GiftCardController extends Controller
{
    use Upload, Notify;

    public function giftCardList()
    {
        $data['manageCard'] = GiftCard::with(['details', 'services'])->withCount('activeServices','activeCodes')->latest()->get();
        return view('admin.gift_card.giftCardList', $data);
    }

    public function giftCardCreate()
    {
        $data['languages'] = Language::all();
        return view('admin.gift_card.giftCardCreate', $data);
    }

    public function giftCardStore(Request $request, $language)
    {
        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));
        DB::beginTransaction();
        try {
            if ($request->has('image')) {
                $purifiedData['image'] = $request->image;
            }

            $rules = [
                'name.*' => 'required|max:40',
                'details.*' => 'required',
                'image' => 'required|mimes:jpg,jpeg,png',
            ];
            $message = [
                'name.*.required' => 'Name field is required',
                'name.*.max' => 'This field may not be greater than :max characters',
                'image.required' => 'Image is required',
                'details.*.required' => 'Details field is required',
            ];

            $validate = Validator::make($purifiedData, $rules, $message);

            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }

            $giftCard = new GiftCard();


            if (isset($purifiedData['discount_amount'])) {
                $giftCard->discount_amount = $purifiedData['discount_amount'];
            }

            if (isset($purifiedData['discount_type'])) {
                $giftCard->discount_type = $purifiedData['discount_type'];
            }


            if($request->has('discount_status')){
                $giftCard->discount_status = $request->discount_status;
            }

            if($request->has('featured')){
                $giftCard->featured = $request->featured;
            }
            if($request->has('status')){
                $giftCard->status = $request->status;
            }


            if ($request->hasFile('image')) {
                try {
                    $giftCard->image = $this->uploadImage($purifiedData['image'], config('location.giftCard.path'), config('location.giftCard.size'), null, config('location.giftCard.thumb'));
                } catch (\Exception $exp) {
                    return back()->with('error', 'Image could not be uploaded.');
                }
            }

            if ($request->hasFile('thumb')) {
                try {
                    $giftCard->thumb = $this->uploadImage($request->thumb, config('location.giftCard.path'), config('location.giftCard.thumb'));
                } catch (\Exception $exp) {
                    return back()->with('error', 'Thumb could not be uploaded.');
                }
            }


            $giftCard->save();

            $giftCard->details()->create([
                'language_id' => $language,
                'name' => @$purifiedData["name"][$language],
                'details' => @$purifiedData["details"][$language],
            ]);

            DB::commit();

            return back()->with('success', 'Gift Card Successfully Saved');
        } catch (\Exception$e) {
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }

    }

    public function giftCardEdit($id)
    {
        $languages = Language::all();
        $cardDetails = GiftCardDetail::with('giftCard:id,discount_amount,status,discount_status,featured,discount_type,image,thumb')->whereGift_cards_id($id)->get()->groupBy('language_id');

        return view('admin.gift_card.giftCardEdit', compact('languages', 'cardDetails', 'id'));
    }

    public function giftCardUpdate(Request $request, $id, $language_id)
    {

        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));
        DB::beginTransaction();

        try {
            if ($request->has('image')) {
                $purifiedData['image'] = $request->image;
            }
            $rules = [
                'name.*' => 'required|max:40',
                'details.*' => 'required',
            ];
            $message = [
                'name.*.required' => 'Name field is required',
                'name.*.max' => 'This field may not be greater than :max characters',
                'details.*.required' => 'Details field is required',
            ];

            $validate = Validator::make($purifiedData, $rules, $message);
            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }
            $giftCard = GiftCard::findOrFail($id);

            if ($request->hasFile('image')) {
                $giftCard->image = $this->uploadImage($purifiedData['image'], config('location.giftCard.path'), config('location.giftCard.size'), $giftCard->image);
            }

            if ($request->hasFile('thumb')) {
                $giftCard->thumb = $this->uploadImage($request->thumb, config('location.giftCard.path'), config('location.giftCard.thumb'), $giftCard->thumb);
            }

            if($request->has('discount_status')){
                $giftCard->discount_status = $request->discount_status;
            }

            if($request->has('featured')){
                $giftCard->featured = $request->featured;
            }
            if($request->has('status')){
                $giftCard->status = $request->status;
            }


            if (isset($purifiedData['discount_amount'])) {
                $giftCard->discount_amount = $purifiedData['discount_amount'];
            }

            if (isset($purifiedData['discount_type'])) {
                $giftCard->discount_type = $purifiedData['discount_type'];
            }


            $giftCard->save();

            $giftCard->details()->updateOrCreate([
                'language_id' => $language_id
            ],
                [
                    'name' => $purifiedData["name"][$language_id],
                    'details' => $purifiedData["details"][$language_id],
                ]
            );
            DB::commit();

            return back()->with('success', 'Gift Card Successfully Updated');

        } catch (\Exception$e) {
            DB::rollBack();
             return back()->with('error',$e->getMessage());
        }

    }

    public function giftCardDelete($id)
    {
        $giftCard = GiftCard::findOrFail($id);

        if(0 < count($giftCard->services)){
            session()->flash('warning','This gift card has a lot of services');
            return back();
        }

        $old_image = $giftCard->image;
        $location = config('location.giftCard.path');

        if (!empty($old_image)) {
            @unlink($location . '/' . $old_image);
        }

        if (!empty($giftCard->thumb)) {
            @unlink($location . '/' . $giftCard->thumb);
        }

        $giftCard->delete();
        return back()->with('success', 'Gift Card has been deleted');
    }

    public function activeMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Gift Card.');
            return response()->json(['error' => 1]);
        } else {
            GiftCard::whereIn('id', $request->strIds)->update([
                'status' => 1,
            ]);
            session()->flash('success', 'Status has been active');
            return response()->json(['success' => 1]);
        }
    }

    public function inactiveMultiple(Request $request)
    {

        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Gift Card.');
            return response()->json(['error' => 1]);
        } else {
            GiftCard::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);
            session()->flash('success', 'Status has been deactive');
            return response()->json(['success' => 1]);

        }
    }

    public function giftCardServicesStore(Request $request)
    {

        $purifiedData = Purify::clean($request->except('_token', '_method'));

        try {
            $rules = [
                'name.*' => 'required|max:40',
                'price.*' => 'required',
                'gift_card_id' => 'required',
            ];
            $message = [
                'name.*.required' => 'Name field is required',
                'price.*.required' => 'Price field is required',
                'gift_card_id.*.required' => 'Gift Card field is required',
            ];

            $validate = Validator::make($purifiedData, $rules, $message);

            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }

            $giftCardService = new GiftCardService();
            $giftCardService->gift_cards_id = $purifiedData['gift_card_id'];
            $giftCardService->name = @$purifiedData['name'];
            $giftCardService->price = @$purifiedData['price'];
            $giftCardService->status = (isset($purifiedData['status']) && $purifiedData['status'] == 'on') ? 1 : 0;
            $giftCardService->save();

            return back()->with('success', 'Added Successfully');

        } catch (\Exception$e) {
            return back();
        }
    }

    public function giftCardServiceUpdate(Request $request, $id)
    {

        $purifiedData = Purify::clean($request->except('_token', '_method'));
        try {
            $rules = [
                'name.*' => 'required|max:40',
                'price.*' => 'required',
            ];
            $message = [
                'name.*.required' => 'Name field is required',
                'price.*.required' => 'Price field is required',
            ];

            $validate = Validator::make($purifiedData, $rules, $message);

            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }


            $giftCardService = GiftCardService::findOrFail($id);

            $giftCardService->name = @$purifiedData['name'];
            $giftCardService->price = @$purifiedData['price'];
            $giftCardService->gift_cards_id = @$purifiedData['gift_card_id'];
            $giftCardService->status = (isset($purifiedData['status']) && $purifiedData['status'] == 'on') ? 1 : 0;
            $giftCardService->save();

            return back()->with('success', 'Updated Successfully');

        } catch (\Exception$e) {
            return back();
        }
    }

    public function giftCardServiceList($serviceId)
    {
        $giftCardService = GiftCardService::with(['giftCard'])->where('id', $serviceId)->firstOrFail();

        $data['giftCardService'] = $giftCardService;
        $data['giftCardId'] = $giftCardService->gift_cards_id;
        $data['serviceId'] = $giftCardService->id;
        $data['giftCardServiceCode'] = GiftCardCode::with('giftCardService')->where('gift_card_service_id', $serviceId)->whereIn('status',['0','1'])->get();

        return view('admin.gift_card.codeList', $data);
    }

    public function giftCardServiceCodeStore($giftCardId, $serviceId, Request $request)
    {
        $purifiedData = Purify::clean($request->except('_token', '_method'));

        $codes = array_unique($request->code);

        try {


            if ($request->has('code')) {
                for ($a = 0; $a < count($codes); $a++) {

                    $giftCardCode = GiftCardCode::firstOrCreate([
                        'code' => $codes[$a]
                    ]);
                    $giftCardCode->gift_card_id = $giftCardId;
                    $giftCardCode->gift_card_service_id = $serviceId;

                    $giftCardCode->save();
                }
            }


            session()->flash('success', 'Code Successfully Saved');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
        return back();
    }

    public function giftCardServiceCodeUpdate(Request $request, $id)
    {

        $purifiedData = Purify::clean($request->except('_token', '_method'));
        try {


            $giftCardCode = GiftCardCode::findOrFail($id);

            $giftCardCode->code = @$purifiedData['code'];
            $giftCardCode->status = (isset($purifiedData['status']) && $purifiedData['status'] == 'on') ? 1 : 0;
            $giftCardCode->save();

            return back()->with('success', 'Updated Successfully');

        } catch (\Exception$e) {
            return back();
        }
    }

    public function giftCardServiceCodeDelete($id)
    {
        $giftCardCode = GiftCardCode::findOrFail($id);

        $giftCardCode->delete();
        return back()->with('success', 'Code has been deleted');
    }

    public function giftCardServiceCodeActiveMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Code.');
            return response()->json(['error' => 1]);
        } else {
            GiftCardCode::whereIn('id', $request->strIds)->update([
                'status' => 1,
            ]);
            session()->flash('success', 'Status has been active');
            return response()->json(['success' => 1]);
        }
    }

    public function giftCardServiceCodeInactiveMultiple(Request $request)
    {

        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Code.');
            return response()->json(['error' => 1]);
        } else {
            GiftCardCode::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);
            session()->flash('success', 'Status has been deactive');
            return response()->json(['success' => 1]);

        }
    }

    public function giftCardServiceCodeDeleteMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Code.');
            return response()->json(['error' => 1]);
        } else {
            GiftCardCode::whereIn('id', $request->strIds)->delete();

            session()->flash('success', 'Successfully Deleted');
            return response()->json(['success' => 1]);

        }
    }


    public function uploadBulkGiftCardCode(Request $request)
    {
        $giftCardService = GiftCardService::where('id', $request->serviceId)->firstOrFail();
        try {
            if($request->file->getClientOriginalExtension() != 'csv'){
                 throw new \Exception('Only accepted .csv files');
            }
            $file = fopen($request->file->getRealPath(),'r');

            while ($csvLine = fgetcsv($file)){
                GiftCardCode::firstOrCreate(
                    ['code'  => $csvLine[0]],
                    [
                        'gift_card_id' => $giftCardService->gift_cards_id,
                        'gift_card_service_id' => $giftCardService->id,
                    ]
                );
            }
            session()->flash('success', 'Imported Successfully');

        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
        return redirect()->route('admin.giftCard.serviceCode', $giftCardService->id);
    }

    public function sampleFiles()
    {
        $file = 'gift-card-sample.csv';
        $full_path = 'assets/' . $file;
        $title = 'sample';
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }




}
