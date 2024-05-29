<?php

namespace App\Http\Controllers;

use App\Models\CategoryService;
use App\Models\Content;
use App\Models\Fund;
use App\Models\SellPost;
use App\Models\Gateway;
use App\Models\GiftCard;
use App\Models\GiftCardService;
use App\Models\Language;
use App\Models\SellPostCategory;
use App\Models\SellPostOffer;
use App\Models\Template;
use App\Models\GameVoucher;
use App\Models\TopUpSell;
use App\Models\User;
use App\Models\VoucherService;
use App\Models\Subscriber;
use App\Http\Traits\Notify;
use Illuminate\Http\Request;
use App\Models\ContentDetails;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;
use Facades\App\Services\BasicService;

class FrontendController extends Controller
{
    use Notify;

    public function __construct()
    {
        $this->theme = template();
    }

    public function index()
    {
        $templateSection = ['top-up', 'about-us', 'voucher', 'why-choose-us', 'gift-card', 'faq', 'sell-post', 'pricing', 'whats-clients-say', 'blog', 'contact-us'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $contentSection = ['slider', 'about-us', 'why-choose-us', 'faq', 'statistics', 'whats-clients-say', 'blog', 'contact-us'];
        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');


        $data['top_up_games'] = Category::with('details:category_id,name')->whereStatus(1)->where('featured',1)->limit(12)->orderBy('id', 'desc')->get();
        $data['vouchers'] = GameVoucher::with('details:game_vouchers_id,name')->whereStatus(1)->where('featured',1)->limit(12)->orderBy('id', 'desc')->get();
        $data['giftCard'] = GiftCard::with('details:gift_cards_id,name')->whereStatus(1)->where('featured',1)->limit(12)->orderBy('id', 'desc')->get();
        $data['sellPost'] = SellPostCategory::with('activePost')->whereStatus(1)->limit(6)->orderBy('id', 'desc')->get();


        return view($this->theme . 'home', $data);
    }


    public function about()
    {

        $templateSection = ['about-us', 'why-choose-us', 'faq', 'whats-clients-say'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $contentSection = ['about-us', 'why-choose-us', 'faq', 'whats-clients-say'];
        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');
        return view($this->theme . 'about', $data);
    }

    public function pricing()
    {

        $templateSection = ['pricing'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $contentSection = ['pricing'];
        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');
        return view($this->theme . 'pricing', $data);
    }


    public function blog()
    {
        $data['title'] = "Blog";
        $contentSection = ['blog'];

        $templateSection = ['blog'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');
        return view($this->theme . 'blog', $data);
    }

    public function blogDetails($slug = null, $id)
    {
        $getData = Content::findOrFail($id);
        $contentSection = [$getData->name];
        $contentDetail = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->where('content_id', $getData->id)
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');


        $singleItem['title'] = @$contentDetail[$getData->name][0]->description->title;
        $singleItem['description'] = @$contentDetail[$getData->name][0]->description->description;
        $singleItem['date'] = dateTime(@$contentDetail[$getData->name][0]->created_at, 'd M, Y');
        $singleItem['image'] = getFile(config('location.content.path') . @$contentDetail[$getData->name][0]->content->contentMedia->description->image);


        $contentSectionPopular = ['blog'];
        $popularContentDetails = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->where('content_id', '!=', $getData->id)
            ->whereHas('content', function ($query) use ($contentSectionPopular) {
                return $query->whereIn('name', $contentSectionPopular);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');


        return view($this->theme . 'blogDetails', compact('singleItem', 'popularContentDetails'));
    }


    public function faq()
    {

        $templateSection = ['faq'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $contentSection = ['faq'];
        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');

        $data['increment'] = 1;
        return view($this->theme . 'faq', $data);
    }

    public function contact()
    {
        $templateSection = ['contact-us'];
        $templates = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');
        $title = 'Contact Us';
        $contact = @$templates['contact-us'][0]->description;

        return view($this->theme . 'contact', compact('title', 'contact'));
    }

    public function contactSend(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:91',
            'subject' => 'required|max:100',
            'message' => 'required|max:1000',
        ]);
        $requestData = Purify::clean($request->except('_token', '_method'));

        $basic = (object)config('basic');
        $basicEmail = $basic->sender_email;

        $name = $requestData['name'];
        $email_from = $requestData['email'];
        $subject = $requestData['subject'];
        $message = $requestData['message'] . "<br>Regards<br>" . $name;
        $from = $email_from;

        $headers = "From: <$from> \r\n";
        $headers .= "Reply-To: <$from> \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $to = $basicEmail;

        if (@mail($to, $subject, $message, $headers)) {
            // echo 'Your message has been sent.';
        } else {
            //echo 'There was a problem sending the email.';
        }

        return back()->with('success', 'Mail has been sent');
    }

    public function getLink($getLink = null, $id)
    {
        $getData = Content::findOrFail($id);

        $contentSection = [$getData->name];
        $contentDetail = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->where('content_id', $getData->id)
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');

        $title = @$contentDetail[$getData->name][0]->description->title;
        $description = @$contentDetail[$getData->name][0]->description->description;
        return view($this->theme . 'getLink', compact('contentDetail', 'title', 'description'));
    }

    public function subscribe(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:255|unique:subscribers'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect(url()->previous() . '#subscribe')->withErrors($validator);
        }
        $data = new Subscriber();
        $data->email = $request->email;
        $data->save();
        return redirect(url()->previous() . '#subscribe')->with('success', 'Subscribed Successfully');
    }

    public function language($code)
    {
        $language = Language::where('short_name', $code)->first();
        if (!$language) $code = 'US';
        session()->put('trans', $code);
        session()->put('rtl', $language ? $language->rtl : 0);
        return redirect()->back();
    }


    public function topUpDetails($slug = 'game-top', $id)
    {
        $data['topUpDetails'] = Category::with('details:category_id,name,details,instruction')->where('status', 1)->findOrFail($id);
        $data['gateways'] = Gateway::whereStatus(1)->orderBy('sort_by')->get();
        return view($this->theme . 'top-up-details', $data);
    }

    public function voucherDetails($slug = 'voucher', $id)
    {
        $data['voucherDetails'] = GameVoucher::with(['details:game_vouchers_id,name,details', 'activeServices.voucherActiveCodes'])->where('status', 1)->findOrFail($id);

        $data['gateways'] = Gateway::whereStatus(1)->orderBy('sort_by')->get();
        return view($this->theme . 'voucher-details', $data);
    }

    public function ajaxCheckTopUpCalc(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'gatewayId' => 'required',
            'serviceId' => 'required'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $gate = Gateway::where('id', $request->gatewayId)->where('status', 1)->first();
        if (!$gate) {
            return response()->json(['error' => 'Invalid Gateway'], 422);
        }

        $service = CategoryService::with('category')->whereStatus(1)
            ->whereHas('category', function ($query) {
                $query->where('status', 1);
            })->findOrFail($request->serviceId);

        if (!$service) {
            return response()->json(['error' => 'Please select a recharge option'], 422);
        }


        $basic = (object)config('basic');

        $serviceCategory = $service->category;

        $discount = 0;

        if ($serviceCategory->discount_status == 1) {
            if ($serviceCategory->discount_type == 0) {
                // fixed Discount
                $discount = $serviceCategory->discount_amount;
            } else {
                // percent Discount
                $discount = ($service->price * $serviceCategory->discount_amount) / 100;
            }
        }

        $reqAmount = $service->price;
        $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
        $payable = getAmount($reqAmount + $charge) - $discount;
        $final_amo = getAmount($payable * $gate->convention_rate);

        if (1000 > $gate->id) {
            $method_currency = (checkTo($gate->currencies, $gate->currency) == 1) ? 'USD' : $gate->currency;
            $isCrypto = (checkTo($gate->currencies, $gate->currency) == 1) ? true : false;
        } else {
            $method_currency = $gate->currency;
            $isCrypto = false;
        }


        return [
            'amount' => $basic->currency_symbol . '' . getAmount($reqAmount, 2),
            'charge' => $basic->currency_symbol . '' . getAmount($charge, 2),
            'subtotal' => $basic->currency_symbol . '' . getAmount($reqAmount + $charge, 2),
            'discount' => $basic->currency_symbol . '' . getAmount($discount, 2),
            'payable' => $basic->currency_symbol . '' . getAmount($payable, 2),
            'gateway_currency' => trans($gate->currency),
            'isCrypto' => $isCrypto,
            'in' => trans("You need to pay ") . getAmount($final_amo, 2) . ' ' . $method_currency . ' By ' . $gate->name,
        ];

    }

    public function ajaxCheckVoucherCalc(Request $request)


    {
        $validator = validator()->make($request->all(), [
            'gatewayId' => 'required',
            'serviceId' => 'required'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $gate = Gateway::where('id', $request->gatewayId)->where('status', 1)->first();
        if (!$gate) {
            return response()->json(['error' => 'Invalid Gateway'], 422);
        }

        $service = VoucherService::with('voucher')->whereStatus(1)
            ->whereHas('voucher', function ($query) {
                $query->where('status', 1);
            })->findOrFail($request->serviceId);

        if (!$service) {
            return response()->json(['error' => 'Please select a recharge option'], 422);
        }
        $basic = (object)config('basic');
        $serviceVoucher = $service->voucher;
        $discount = 0;
        if ($serviceVoucher->discount_status == 1) {
            if ($serviceVoucher->discount_type == 0) {
                // fixed Discount
                $discount = $serviceVoucher->discount_amount;
            } else {
                // percent Discount
                $discount = ($service->price * $serviceVoucher->discount_amount) / 100;
            }
        }

        $reqAmount = $service->price;
        $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
        $payable = getAmount($reqAmount + $charge) - $discount;
        $final_amo = getAmount($payable * $gate->convention_rate);
        if (1000 > $gate->id) {
            $method_currency = (checkTo($gate->currencies, $gate->currency) == 1) ? 'USD' : $gate->currency;
            $isCrypto = (checkTo($gate->currencies, $gate->currency) == 1) ? true : false;
        } else {
            $method_currency = $gate->currency;
            $isCrypto = false;
        }


        return [
            'amount' => $basic->currency_symbol . '' . getAmount($reqAmount, 2),
            'charge' => $basic->currency_symbol . '' . getAmount($charge, 2),
            'subtotal' => $basic->currency_symbol . '' . getAmount($reqAmount + $charge, 2),
            'discount' => $basic->currency_symbol . '' . getAmount($discount, 2),
            'payable' => $basic->currency_symbol . '' . getAmount($payable, 2),
            'gateway_currency' => trans($gate->currency),
            'isCrypto' => $isCrypto,
            'in' => trans("You need to pay ") . getAmount($final_amo, 2) . ' ' . $method_currency . ' By ' . $gate->name,
        ];

    }

    //Gift Card Related Function
    public function giftCardDetails($slug = 'gift-card', $id)
    {
        $data['giftCardDetails'] = GiftCard::with(['details:gift_cards_id,name,details', 'activeServices.giftCardActiveCodes'])->where('status', 1)->findOrFail($id);

        $data['gateways'] = Gateway::whereStatus(1)->orderBy('sort_by')->get();
        return view($this->theme . 'gift-card-details', $data);
    }

    public function ajaxCheckGiftCardCalc(Request $request)


    {
        $validator = validator()->make($request->all(), [
            'gatewayId' => 'required',
            'serviceId' => 'required'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $gate = Gateway::where('id', $request->gatewayId)->where('status', 1)->first();
        if (!$gate) {
            return response()->json(['error' => 'Invalid Gateway'], 422);
        }

        $service = GiftCardService::with('giftCard')->whereStatus(1)
            ->whereHas('giftCard', function ($query) {
                $query->where('status', 1);
            })->findOrFail($request->serviceId);

        if (!$service) {
            return response()->json(['error' => 'Please select a recharge option'], 422);
        }
        $basic = (object)config('basic');
        $serviceGiftCard = $service->giftCard;
        $discount = 0;
        if ($serviceGiftCard->discount_status == 1) {
            if ($serviceGiftCard->discount_type == 0) {
                // fixed Discount
                $discount = $serviceGiftCard->discount_amount;
            } else {
                // percent Discount
                $discount = ($service->price * $serviceGiftCard->discount_amount) / 100;
            }
        }

        $reqAmount = $service->price;
        $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
        $payable = getAmount($reqAmount + $charge) - $discount;
        $final_amo = getAmount($payable * $gate->convention_rate);
        if (1000 > $gate->id) {
            $method_currency = (checkTo($gate->currencies, $gate->currency) == 1) ? 'USD' : $gate->currency;
            $isCrypto = (checkTo($gate->currencies, $gate->currency) == 1) ? true : false;
        } else {
            $method_currency = $gate->currency;
            $isCrypto = false;
        }


        return [
            'amount' => $basic->currency_symbol . '' . getAmount($reqAmount, 2),
            'charge' => $basic->currency_symbol . '' . getAmount($charge, 2),
            'subtotal' => $basic->currency_symbol . '' . getAmount($reqAmount + $charge, 2),
            'discount' => $basic->currency_symbol . '' . getAmount($discount, 2),
            'payable' => $basic->currency_symbol . '' . getAmount($payable, 2),
            'gateway_currency' => trans($gate->currency),
            'isCrypto' => $isCrypto,
            'in' => trans("You need to pay ") . getAmount($final_amo, 2) . ' ' . $method_currency . ' By ' . $gate->name,
        ];

    }

    public function shop(Request $request)
    {
        if (config('basic.top_up')) {
            $topUp = Category::with(['details'])->withCount('totalSold')->whereStatus(1)->get();
        } else {
            $topUp = collect();
        }

        if (config('basic.voucher')) {
            $voucher = GameVoucher::with(['details'])->withCount('totalSold')->whereStatus(1)->get();
        } else {
            $voucher = collect();
        }
        if (config('basic.gift_card')) {
            $giftCard = GiftCard::with(['details'])->withCount('totalSold')->whereStatus(1)->get();
        }else {
            $giftCard = collect();
        }
        $collect = collect();
        if ($request->has('sortByCategory') && isset($request->sortByCategory) && $request->has('search')) {
            foreach (explode(',', $request->sortByCategory) as $value) {
                if ($value == 'topUp') {
                    $collect->push($topUp);
                } elseif ($value == 'voucher') {
                    $collect->push($voucher);
                } elseif ($value == 'giftCard') {
                    $collect->push($giftCard);
                }
            }

            $items = $collect->collapse();

            $search = strtolower($request->search);
            $items = $items->filter(function ($item) use ($search) {
                $title = strtolower(optional($item->details)->name);
                return str_contains($title, $search);
            });
            $data['items'] = $items->paginate(config('basic.paginate'));

            return view($this->theme . 'shop', $data);


        } elseif ($request->has('sortByCategory') && isset($request->sortByCategory)) {
            foreach (explode(',', $request->sortByCategory) as $value) {
                if ($value == 'topUp') {
                    $collect->push($topUp);
                } elseif ($value == 'voucher') {
                    $collect->push($voucher);
                } elseif ($value == 'giftCard') {
                    $collect->push($giftCard);
                }
            }
        } else {
            $collect->push($topUp, $voucher, $giftCard);
        }
        $result = $collect->collapse();


        $items = $result->sortByDesc('featured');

        if ($request->has('sortBy')) {
            $sortBy = $request->sortBy;
            if ($sortBy == 'date') {
                $items = $items->sortBy('updated_at');
            }
            if ($sortBy == 'latest') {
                $items = $items->sortByDesc('updated_at');
            }
            if ($sortBy == 'discount') {
                $items = $items->where('discount_status', 1)->sortByDesc('discount_amount');
            }
            if ($sortBy == 'featured') {
                $items = $items->sortByDesc('featured');
            }
            if ($sortBy == 'popular') {
                $items = $items->sortByDesc('total_sold_count');
            }
        }

        if ($request->has('search')) {
            $search = strtolower($request->search);
            $items = $items->filter(function ($item) use ($search) {
                $title = strtolower(optional($item->details)->name);
                return str_contains($title, $search);
            });
        }


        $data['items'] = $items->paginate(config('basic.paginate'));

        return view($this->theme . 'shop', $data);
    }

    public function buy(Request $request)
    {

        $selectQuery = DB::table('sell_posts');
        $max = $selectQuery->max('price');
        $min = $selectQuery->min('price');


        $query = SellPost::query();
        $data['sellPost'] = $query->when(request('sortByCategory', false), function ($q, $sortByCategory) {
            $newArr = explode(',', $sortByCategory);
            $q->whereHas('category', function ($q) {
                $q->where('status', 1);
            })->whereIn('category_id', $newArr);

        })->when(request('search', false), function ($q, $search) {
            $q->where('title', 'LIKE', "%$search%");
        })
            ->where('status', 1)
            ->where('payment_status', '!=', 1)
            ->when(request('minPrice', false), function ($q, $minPrice) {
                $maxPrice = \request('maxPrice');
                $q->whereBetween('price', [$minPrice, $maxPrice]);
            })
            ->when(request('sortBy', false), function ($q, $sortBy) {
                if ($sortBy == 'desc') {
                    $q->orderBy('updated_at', 'desc');
                }
                if ($sortBy == 'asc') {
                    $q->orderBy('created_at', 'asc');
                }
                if ($sortBy == 'low_to_high') {
                    $q->orderBy('price', 'asc');
                }
                if ($sortBy == 'high_to_low') {
                    $q->orderBy('price', 'desc');
                }

            })
            ->paginate(5);

        $data['max'] = $max;
        $data['min'] = $min;

        $data['categories'] = SellPostCategory::with('details')->whereHas('activePost')->whereStatus(1)->get();
        return view($this->theme . 'buy', $data);
    }

    public function sellPostList($slug = 'sell-post', $id, Request $request)
    {

        $data['category'] = SellPostCategory::with('details')->whereStatus(1)->get();
        $data['sellPost'] = SellPost::whereStatus(1)->where('payment_status', '!=', 1)->whereCategory_id($id)->orderBy('updated_at', 'desc')->get();

        if ($request->has('sortBy')) {
            $sortBy = $request->sortBy;
            if ($sortBy == 'low_to_high') {
                $data['sellPost'] = SellPost::whereStatus(1)->whereCategory_id($id)->where('payment_status', '!=', 1)->orderBy('price', 'asc')->get();
            }
            if ($sortBy == 'high_to_low') {
                $data['sellPost'] = SellPost::whereStatus(1)->whereCategory_id($id)->where('payment_status', '!=', 1)->orderBy('price', 'desc')->get();
            }
            if ($sortBy == 'latest') {
                $data['sellPost'] = SellPost::whereStatus(1)->whereCategory_id($id)->where('payment_status', '!=', 1)->orderBy('updated_at', 'desc')->get();
            }
        }

        if ($request->has('search')) {
            $search = strtolower($request->search);
            $data['sellPost'] = SellPost::whereStatus(1)->whereCategory_id($id)->where('payment_status', '!=', 1)->where('title', 'LIKE', "%{$search}%")->get();
        }

        $data['items'] = $data['sellPost']->paginate(config('basic.paginate'));
        return view($this->theme . 'sell-post', $data);
    }

    public function sellPostDetails($slug = 'sell-post-details', $id)
    {
        $loginUser = SellPost::whereId($id)->pluck('user_id');

        if (Auth::check() == true && Auth::id() == $loginUser[0]) {
            $sellPost = SellPost::whereId($id)->first();
        } else {
            $sellPost = SellPost::where('id', $id)
                ->where('status', 1)
                ->first();
        }

        $data['sellPostOffer'] = SellPostOffer::whereSell_post_id($id)->orderBy('amount', 'desc')->take(3)->get();
        $data['price'] = $sellPost->price;
        if (Auth::check()) {
            $user = Auth::user();
            $checkMyProposal = SellPostOffer::where([
                'user_id' => $user->id,
                'sell_post_id' => $sellPost->id,
                'status' => 1,
                'payment_status' => 0,
            ])->first();
            if ($checkMyProposal) {
                $data['price'] = (int)$checkMyProposal->amount;
            }
        }
        $data['sellPost'] = $sellPost;

        return view($this->theme . 'sell-post-details', $data);
    }

    public function ajaxCheckSellPostCalc(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'sellPostId' => 'required',
            'gatewayId' => 'required',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $gate = Gateway::where('id', $request->gatewayId)->where('status', 1)->first();
        if (!$gate) {
            return response()->json(['error' => 'Invalid Gateway'], 422);
        }

        $sellPostId = $request->sellPostId;
        $sellPost = SellPost::where('id', $sellPostId)
            ->where('status', 1)
            ->first();

        if (!$sellPost) {
            return response()->json(['error' => 'This post already sold or not available to sell'], 422);
        }


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


        $basic = (object)config('basic');
        $discount = 0;

        $reqAmount = $price;
        $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
        $payable = getAmount($reqAmount + $charge) - $discount;
        $final_amo = getAmount($payable * $gate->convention_rate);

        if (1000 > $gate->id) {
            $method_currency = (checkTo($gate->currencies, $gate->currency) == 1) ? 'USD' : $gate->currency;
            $isCrypto = (checkTo($gate->currencies, $gate->currency) == 1) ? true : false;
        } else {
            $method_currency = $gate->currency;
            $isCrypto = false;
        }


        return [
            'amount' => $basic->currency_symbol . '' . getAmount($reqAmount, 2),
            'charge' => $basic->currency_symbol . '' . getAmount($charge, 2),
            'subtotal' => $basic->currency_symbol . '' . getAmount($reqAmount + $charge, 2),
            'discount' => $basic->currency_symbol . '' . getAmount($discount, 2),
            'payable' => $basic->currency_symbol . '' . getAmount($payable, 2),
            'gateway_currency' => trans($gate->currency),
            'isCrypto' => $isCrypto,
            'in' => trans("You need to pay ") . getAmount($final_amo, 2) . ' ' . $method_currency . ' By ' . $gate->name,
        ];

    }

}
