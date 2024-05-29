<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Language;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use Illuminate\Http\Request;
use App\Models\CategoryDetails;
use App\Models\CategoryService;
use App\Http\Controllers\Controller;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use Upload, Notify;

    public function category()
    {
        $manageCategory = Category::with(['details'])->withCount('activeServices')->latest()->get();
        return view('admin.category.categoryList', compact('manageCategory'));
    }

    public function categoryCreate()
    {
        $languages = Language::all();
        return view('admin.category.categoryCreate', compact('languages'));
    }

    public function categoryStore(Request $request, $language)
    {

        $purifiedData = Purify::clean($request->except('image', 'instruction_image', '_token', '_method'));
        DB::beginTransaction();
        try {
            if ($request->has('image')) {
                $purifiedData['image'] = $request->image;
            }

            if ($request->has('instruction_image')) {
                $purifiedData['instruction_image'] = $request->instruction_image;
            }

            $rules = [
                'name.*' => 'required|max:40',
                'details.*' => 'required',
                'appStoreLink' => 'url',
                'playStoreLink' => 'url',
                'image' => 'required|mimes:jpg,jpeg,png',
            ];
            $message = [
                'name.*.required' => 'Name field is required',
                'name.*.max' => 'This field may not be greater than :max characters',
                'image.required' => 'Image is required',
                'appStoreLink.url' => 'This App Store Link field must be an url',
                'playStoreLink.url' => 'This Play Store Link field must be an url',
                'details.*.required' => 'Details field is required',
            ];

            $validate = Validator::make($purifiedData, $rules, $message);

            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }

            $category = new Category();

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

            if (isset($purifiedData['appStoreLink'])) {
                $category->appStoreLink = @$purifiedData['appStoreLink'];
            }
            if (isset($purifiedData['playStoreLink'])) {
                $category->playStoreLink = @$purifiedData['playStoreLink'];
            }
            if (isset($purifiedData['status'])) {
                $category->status = isset($purifiedData['status']) ? 1 : 0;
            }
            if (isset($purifiedData['field_name'])) {
                $category->form_field = $input_form;
            }


            if($request->has('discount_status')){
                $category->discount_status = $request->discount_status;
            }

            if($request->has('featured')){
                $category->featured = $request->featured;
            }
            if($request->has('status')){
                $category->status = $request->status;
            }

            if (isset($purifiedData['discount_amount'])) {
                $category->discount_amount = $purifiedData['discount_amount'];
            }

            if (isset($purifiedData['discount_type'])) {
                $category->discount_type = isset($purifiedData['discount_type']) ? 1 : 0;
            }

            if ($request->hasFile('image')) {
                try {
                    $category->image = $this->uploadImage($purifiedData['image'], config('location.category.path'), config('location.category.size'));
                } catch (\Exception $exp) {
                    return back()->with('error', 'Image could not be uploaded.');
                }
            }

            if ($request->hasFile('thumb')) {
                try {
                    $category->thumb = $this->uploadImage($request->thumb, config('location.category.path'), config('location.category.thumb'));
                } catch (\Exception $exp) {
                    return back()->with('error', 'Thumb could not be uploaded.');
                }
            }

            if ($request->hasFile('instruction_image')) {
                try {
                    $category->instruction_image = $this->uploadImage($purifiedData['instruction_image'],config('location.category.path'));
                } catch (\Exception $exp) {
                    return back()->with('error', 'Instruction Image could not be uploaded.');
                }
            }

            $category->save();

            $category->details()->create([
                'language_id' => $language,
                'name' => @$purifiedData["name"][$language],
                'details' => @$purifiedData["details"][$language],
                'instruction' => @$purifiedData["instruction_text"][$language],
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
        $categoryDetails = CategoryDetails::with('Category')->where('category_id', $id)->get()->groupBy('language_id');
        return view('admin.category.categoryEdit', compact('languages', 'categoryDetails', 'id'));
    }


    public function categoryUpdate(Request $request, $id, $language_id)
    {
        $purifiedData = Purify::clean($request->except('image', 'instruction_image', '_token', '_method'));
        DB::beginTransaction();

        try {
            if ($request->has('image')) {
                $purifiedData['image'] = $request->image;
            }

            if ($request->has('instruction_image')) {
                $purifiedData['instruction_image'] = $request->instruction_image;
            }

            $rules = [
                'name.*' => 'required|max:40',
                'details.*' => 'required',
                'instruction_text.*' => 'required',
                'appStoreLink' => 'url',
                'playStoreLink' => 'url',
            ];
            $message = [
                'name.*.required' => 'Name field is required',
                'name.*.max' => 'This field may not be greater than :max characters',
                'appStoreLink.url' => 'This App Store Link field must be an url',
                'playStoreLink.url' => 'This Play Store Link field must be an url',
                'details.*.required' => 'Details field is required',
                'instruction_text.*.required' => 'Instrucxtion field is required',
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


            $category = Category::findOrFail($id);

            if ($request->hasFile('image')) {
                $category->image = $this->uploadImage($purifiedData['image'], config('location.category.path'), config('location.category.size'), $category->image, config('location.category.thumb'));
            }

            if ($request->hasFile('thumb')) {
                $category->thumb = $this->uploadImage($request->thumb, config('location.category.path'), config('location.category.thumb'), $category->thumb);
            }

            if ($request->hasFile('instruction_image')) {
                $category->instruction_image = $this->uploadImage($purifiedData['instruction_image'], config('location.category.path'),null, $category->instruction_image);
            }

            if (isset($purifiedData['appStoreLink'])) {
                $category->appStoreLink = @$purifiedData['appStoreLink'];
            }
            if (isset($purifiedData['playStoreLink'])) {
                $category->playStoreLink = @$purifiedData['playStoreLink'];
            }




            if (isset($purifiedData['field_name'])) {
                $category->form_field = $input_form;
            }

            if (isset($purifiedData['discount_amount'])) {
                $category->discount_amount = $purifiedData['discount_amount'];
            }

            if (isset($purifiedData['discount_type'])) {
                $category->discount_type = $purifiedData['discount_type'];
            }

            if($request->has('discount_status')){
                $category->discount_status = $request->discount_status;
            }

            if($request->has('featured')){
                $category->featured = (int) $request->featured;
            }
            if($request->has('status')){
                $category->status = $request->status;
            }


            $category->save();



            $category->details()->updateOrCreate([
                'language_id' => $language_id
            ],
                [
                    'name' => @$purifiedData["name"][$language_id],
                    'details' => @$purifiedData["details"][$language_id],
                    'instruction' => @$purifiedData["instruction_text"][$language_id],
                ]
            );
            DB::commit();

            return back()->with('success', 'Category Successfully Updated');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }

    }


    public function categoryDelete($id)
    {
        $categoryData = Category::findOrFail($id);

        if(0 < count($categoryData->categoryService)){
            return back()->with('warning', 'Category has a lot of services');
        }
        if(0 < $categoryData->topUpSells->where('payment_status',1)->count()){
            session()->flash('warning','This services has a lot of transactions');
            return back();
        }


        $old_image = $categoryData->image;
        $location = config('location.category.path');
        if (!empty($old_image)) {
            @unlink($location . '/' . $old_image);
        }
        if (!empty($categoryData->thumb)) {
            @unlink($location . '/' . $categoryData->thumb);
        }
        if (!empty($categoryData->instruction_image)) {
            @unlink($location . '/' . $categoryData->instruction_image);
        }

        $categoryData->delete();
        return back()->with('success', 'Category has been deleted');
    }


    public function gameServices($id)
    {
        $data['game'] = Category::findOrFail($id);
        $data['gameServices'] = CategoryService::whereCategory_id($id)->orderBy('id', 'DESC')->get();
        return view('admin.category.service', $data);

    }

    public function gameServicesStore(Request $request, $id)
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

            $category_service = new CategoryService();
            $category_service->category_id = $id;
            $category_service->name = @$purifiedData['name'];
            $category_service->price = @$purifiedData['price'];
            $category_service->status = (isset($purifiedData['status']) && $purifiedData['status'] == 'on') ? 1 : 0;
            $category_service->save();

            return back()->with('success', 'Added Successfully');

        } catch (\Exception$e) {
            return back();
        }

    }

    public function gameServicesEdit(Request $request, $id)
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


            $category_service = CategoryService::findOrFail($id);

            $category_service->name = @$purifiedData['name'];
            $category_service->price = @$purifiedData['price'];
            $category_service->status = (isset($purifiedData['status']) && $purifiedData['status'] == 'on') ? 1 : 0;
            $category_service->save();

            return back()->with('success', 'Updated Successfully');

        } catch (\Exception$e) {
            return back();
        }

    }

    public function gameServicesDelete($id)
    {
        try {
            $category_service = CategoryService::findOrFail($id);

            if(0 < $category_service->topUpSells->where('payment_status',1)->count()){
                session()->flash('warning','This services has a lot of transactions');
                return back();
            }

            $category_service->delete();
            return back()->with('success', 'Deleted Successfully');

        } catch (\Exception$e) {
            return back();
        }

    }

    public function activeGameMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Game.');
            return response()->json(['error' => 1]);
        } else {
            Category::whereIn('id', $request->strIds)->update([
                'status' => 1,
            ]);
            session()->flash('success', 'Status has been active');
            return response()->json(['success' => 1]);
        }
    }

    public function inactiveGameMultiple(Request $request)
    {

        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Game.');
            return response()->json(['error' => 1]);
        } else {
            Category::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);
            session()->flash('success', 'Status has been deactive');
            return response()->json(['success' => 1]);

        }
    }

    public function activeMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select User.');
            return response()->json(['error' => 1]);
        } else {
            CategoryService::whereIn('id', $request->strIds)->update([
                'status' => 1,
            ]);
            session()->flash('success', 'Status has been active');
            return response()->json(['success' => 1]);
        }
    }

    public function inactiveMultiple(Request $request)
    {

        if ($request->strIds == null) {
            session()->flash('error', 'You do not select User.');
            return response()->json(['error' => 1]);
        } else {
            CategoryService::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);
            session()->flash('success', 'Status has been deactive');
            return response()->json(['success' => 1]);

        }
    }

    public function uploadBulkGameCode(Request $request, $id)
    {
        $game = Category::findOrFail($id);
        try {

            if ($request->upload->getClientOriginalExtension() != 'csv') {
                throw new \Exception('Only accepted .csv files');
            }
            $file = fopen($request->upload->getRealPath(), 'r');

            while ($csvLine = fgetcsv($file)) {
                $service = CategoryService::firstOrCreate(
                    ['category_id' => $game->id,
                        'name' => $csvLine[0]
                    ]
                );

                $service->price = $csvLine[1];
                $service->save();
            }
            session()->flash('success', 'Imported Successfully');

        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
        return redirect()->route('admin.gameList.services', $game->id);
    }

    public function gameSampleFiles()
    {
        $file = 'game-services-sample.csv';
        $full_path = 'assets/' . $file;
        $title = 'game-services-sample';
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }


    public function imageDelete($id)
    {
        $categoryImage = Category::findOrFail($id);
        $old_images = $categoryImage->instruction_image;
        $location = config('location.category.path');

        if (!empty($categoryImage->instruction_image)) {
            @unlink($location . '/' . $categoryImage->instruction_image);
        }
        $categoryImage->instruction_image = null;
        $categoryImage->save();
        return back()->with('success', 'Instruction Image has been deleted');
   }
}
