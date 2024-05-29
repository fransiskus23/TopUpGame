<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameVoucher;
use App\Models\GameVoucherDetail;
use App\Models\VoucherCode;
use App\Models\VoucherService;
use App\Models\Language;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GameVoucherController extends Controller
{
    use Upload, Notify;

    public function voucherList()
    {
        $data['manageVoucher'] = GameVoucher::with(['details', 'services'])->withCount('activeServices','activeCodes')->latest()->get();
        return view('admin.game_voucher.voucherList', $data);
    }

    public function gameVoucherCreate()
    {
        $data['languages'] = Language::all();
        return view('admin.game_voucher.voucherCreate', $data);
    }

    public function gameVoucherStore(Request $request, $language)
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

            $gameVoucher = new GameVoucher();


            if($request->has('discount_status')){
                $gameVoucher->discount_status = $request->discount_status;
            }

            if($request->has('featured')){
                $gameVoucher->featured = $request->featured;
            }
            if($request->has('status')){
                $gameVoucher->status = $request->status;
            }

            if (isset($purifiedData['discount_amount'])) {
                $gameVoucher->discount_amount = $purifiedData['discount_amount'];
            }

            if (isset($purifiedData['discount_type'])) {
                $gameVoucher->discount_type = $purifiedData['discount_type'];
            }


            if ($request->hasFile('image')) {
                try {
                    $gameVoucher->image = $this->uploadImage($purifiedData['image'], config('location.voucher.path'), config('location.voucher.size'));
                } catch (\Exception $exp) {
                    return back()->with('error', 'Image could not be uploaded.');
                }
            }
            if ($request->hasFile('thumb')) {
                try {
                    $gameVoucher->thumb = $this->uploadImage($request->thumb, config('location.voucher.path'), config('location.voucher.thumb'));
                } catch (\Exception $exp) {
                    return back()->with('error', 'Thumb could not be uploaded.');
                }
            }


            $gameVoucher->save();

            $gameVoucher->details()->create([
                'language_id' => $language,
                'name' => $purifiedData["name"][$language],
                'details' => $purifiedData["details"][$language],
            ]);

            DB::commit();

            return back()->with('success', 'Voucher Successfully Saved');
        } catch (\Exception$e) {
            DB::rollBack();
           return back()->with('error',$e->getMessage());
        }

    }

    public function gameVoucherEdit($id)
    {
        $languages = Language::all();
        $voucherDetails = GameVoucherDetail::with('gameVoucher:id,discount_amount,status,discount_status,featured,discount_type,image,thumb')->whereGame_vouchers_id($id)->get()->groupBy('language_id');
        return view('admin.game_voucher.voucherEdit', compact('languages', 'voucherDetails', 'id'));
    }

    public function gameVoucherUpdate(Request $request, $id, $language_id)
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


            $gameVoucher = GameVoucher::findOrFail($id);

            if ($request->hasFile('image')) {
                $gameVoucher->image = $this->uploadImage($purifiedData['image'], config('location.voucher.path'), config('location.voucher.size'), $gameVoucher->image);
            }
            if ($request->hasFile('thumb')) {
                $gameVoucher->thumb = $this->uploadImage($request->thumb, config('location.voucher.path'), config('location.voucher.thumb'), $gameVoucher->thumb);
            }




            if (isset($purifiedData['discount_amount'])) {
                $gameVoucher->discount_amount = $purifiedData['discount_amount'];
            }

            if (isset($purifiedData['discount_type'])) {
                $gameVoucher->discount_type = $purifiedData['discount_type'];
            }


            if($request->has('discount_status')){
                $gameVoucher->discount_status = $request->discount_status;
            }

            if($request->has('featured')){
                $gameVoucher->featured = $request->featured;
            }
            if($request->has('status')){
                $gameVoucher->status = $request->status;
            }



            $gameVoucher->save();

            $gameVoucher->details()->updateOrCreate([
                'language_id' => $language_id
            ],
                [
                    'name' => $purifiedData["name"][$language_id],
                    'details' => $purifiedData["details"][$language_id],
                ]
            );
            DB::commit();

            return back()->with('success', 'Voucher Successfully Updated');

        } catch (\Exception$e) {
            DB::rollBack();
             return back()->with('error',$e->getMessage());
        }

    }

    public function gameVoucherDelete($id)
    {
        $gameVoucher = GameVoucher::findOrFail($id);

        if(0 < count($gameVoucher->services)){
            session()->flash('warning','This voucher has a lot of services');
            return back();
        }


        $old_image = $gameVoucher->image;
        $location = config('location.voucher.path');

        if (!empty($old_image)) {
            @unlink($location . '/' . $old_image);
        }
        if (!empty($gameVoucher->thumb)) {
            @unlink($location . '/' . $gameVoucher->thumb);
        }

        $gameVoucher->delete();
        return back()->with('success', 'Voucher has been deleted');
    }

    public function activeMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Voucher.');
            return response()->json(['error' => 1]);
        } else {
            GameVoucher::whereIn('id', $request->strIds)->update([
                'status' => 1,
            ]);
            session()->flash('success', 'Status has been active');
            return response()->json(['success' => 1]);
        }
    }

    public function inactiveMultiple(Request $request)
    {

        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Voucher.');
            return response()->json(['error' => 1]);
        } else {
            GameVoucher::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);
            session()->flash('success', 'Status has been deactive');
            return response()->json(['success' => 1]);

        }
    }

    public function voucherServicesStore(Request $request)
    {

        $purifiedData = Purify::clean($request->except('_token', '_method'));

        try {
            $rules = [
                'name.*' => 'required|max:40',
                'price.*' => 'required',
                'voucher_id' => 'required',
            ];
            $message = [
                'name.*.required' => 'Name field is required',
                'price.*.required' => 'Price field is required',
                'voucher_id.*.required' => 'Voucher field is required',
            ];

            $validate = Validator::make($purifiedData, $rules, $message);

            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }

            $cvoucher_service = new VoucherService();
            $cvoucher_service->game_vouchers_id = $purifiedData['voucher_id'];
            $cvoucher_service->name = @$purifiedData['name'];
            $cvoucher_service->price = @$purifiedData['price'];
            $cvoucher_service->status = (isset($purifiedData['status']) && $purifiedData['status'] == 'on') ? 1 : 0;
            $cvoucher_service->save();

            return back()->with('success', 'Added Successfully');

        } catch (\Exception$e) {
            return back();
        }
    }

    public function voucherServiceUpdate(Request $request, $id)
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


            $voucher_service = VoucherService::findOrFail($id);

            $voucher_service->name = @$purifiedData['name'];
            $voucher_service->price = @$purifiedData['price'];
            $voucher_service->game_vouchers_id = @$purifiedData['voucher_id'];
            $voucher_service->status = (isset($purifiedData['status']) && $purifiedData['status'] == 'on') ? 1 : 0;
            $voucher_service->save();

            return back()->with('success', 'Updated Successfully');

        } catch (\Exception$e) {
            return back();
        }
    }

    public function voucherServiceList($serviceId)
    {
        $voucherService = VoucherService::with(['voucher'])->where('id', $serviceId)->firstOrFail();

        $data['voucherService'] = $voucherService;
        $data['voucherId'] = $voucherService->game_vouchers_id;
        $data['serviceId'] = $voucherService->id;
        $data['voucherServiceCode'] = VoucherCode::with('voucherService')->where('voucher_service_id', $serviceId)->whereIn('status',['0','1'])->get();

        return view('admin.game_voucher.codeList', $data);
    }

    public function voucherServiceCodeStore($voucherId, $serviceId, Request $request)
    {
        $purifiedData = Purify::clean($request->except('_token', '_method'));

        $codes = array_unique($request->code);

        try {

            if ($request->has('code')) {
                for ($a = 0; $a < count($codes); $a++) {

                    $voucherCode =  VoucherCode::firstOrCreate([
                        'code' => $codes[$a]
                    ]);;
                    $voucherCode->voucher_id = $voucherId;
                    $voucherCode->voucher_service_id = $serviceId;
                    $voucherCode->save();
                }
            }


            session()->flash('success', 'Code Successfully Saved');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
        return back();
    }

    public function voucherServiceCodeUpdate(Request $request, $id)
    {

        $purifiedData = Purify::clean($request->except('_token', '_method'));
        try {


            $voucherCode = VoucherCode::findOrFail($id);

            $voucherCode->code = @$purifiedData['code'];
            $voucherCode->status = (isset($purifiedData['status']) && $purifiedData['status'] == 'on') ? 1 : 0;
            $voucherCode->save();

            return back()->with('success', 'Updated Successfully');

        } catch (\Exception$e) {
            return back();
        }
    }

    public function voucherServiceCodeDelete($id)
    {
        $voucherCode = VoucherCode::findOrFail($id);

        $voucherCode->delete();
        return back()->with('success', 'Code has been deleted');
    }

    public function voucherServiceCodeActiveMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Voucher.');
            return response()->json(['error' => 1]);
        } else {
            VoucherCode::whereIn('id', $request->strIds)->update([
                'status' => 1,
            ]);
            session()->flash('success', 'Status has been active');
            return response()->json(['success' => 1]);
        }
    }

    public function voucherServiceCodeInactiveMultiple(Request $request)
    {

        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Voucher.');
            return response()->json(['error' => 1]);
        } else {
            VoucherCode::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);
            session()->flash('success', 'Status has been deactive');
            return response()->json(['success' => 1]);

        }
    }

    public function voucherServiceCodeDeleteMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Code.');
            return response()->json(['error' => 1]);
        } else {
            VoucherCode::whereIn('id', $request->strIds)->delete();

            session()->flash('success', 'Successfully Deleted');
            return response()->json(['success' => 1]);

        }
    }


    public function uploadBulkVoucherCode(Request $request)
    {
        $voucherService = VoucherService::where('id', $request->serviceId)->firstOrFail();
        try {
            if($request->file->getClientOriginalExtension() != 'csv'){
                 throw new \Exception('Only accepted .csv files');
            }
            $file = fopen($request->file->getRealPath(),'r');

            while ($csvLine = fgetcsv($file)){
                VoucherCode::firstOrCreate(
                    ['code'  => $csvLine[0]],
                    [
                        'voucher_id' => $voucherService->game_vouchers_id,
                        'voucher_service_id' => $voucherService->id,
                    ]
                );
            }
            session()->flash('success', 'Imported Successfully');

        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
        return redirect()->route('admin.gameVoucher.serviceCode', $voucherService->id);
    }

    public function sampleFiles()
    {
        $file = 'sample.csv';
        $full_path = 'assets/' . $file;
        $title = 'sample';
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }




}
