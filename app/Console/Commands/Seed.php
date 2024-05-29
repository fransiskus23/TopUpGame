<?php

namespace App\Console\Commands;

use App\Models\GiftCardCode;
use App\Models\GiftCardService;
use App\Models\SellPost;
use App\Models\SellPostCategory;
use App\Models\User;
use App\Models\VoucherCode;
use App\Models\VoucherService;
use Faker\Factory;
use File;
use Illuminate\Console\Command;

class Seed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeder For Demo Voucher Code, Gift Card & Sellpost data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $user = User::first();
        $user->username = 'demouser';
        $user->password = bcrypt('demouser');
        $user->save();

        VoucherService::where('status', 1)->with('voucherActiveCodes')->get()
            ->map(function ($vService) {
                for ($i = count($vService->voucherActiveCodes); $i < 10; $i++) {
                    VoucherCode::create([
                        'voucher_id' => $vService->game_vouchers_id,
                        'voucher_service_id' => $vService->id,
                        'code' => codeRandom(),
                        'status' => 1,
                    ]);
                }
            });


        GiftCardService::where('status', 1)->with('giftCardActiveCodes')->get()
            ->map(function ($gService) {
                for ($i = count($gService->giftCardActiveCodes); $i < 10; $i++) {
                    GiftCardCode::create([
                        'gift_card_id' => $gService->gift_cards_id,
                        'gift_card_service_id' => $gService->id,
                        'code' => strRandom(16),
                        'status' => 1,
                    ]);
                }
            });


        $users = User::toBase()->limit(10)->get(['id'])->map(function ($user) {
            return $user->id;
        })->toArray();


        SellPostCategory::where('status', 1)->with('activePost')->get()
            ->map(function ($category) use ($users) {


                for ($i = count($category->activePost); $i < 5; $i++) {
                    $userId = $users[array_rand($users)];
                    $faker = Factory::create();
                    $category = SellPostCategory::find($category->id);
                    $gameSell = new SellPost();
                    $gameSell->category_id = $category->id;
                    $gameSell->user_id = $userId;

                    $reqField = [];
                    if ($category->form_field != null) {
                        foreach ($category->form_field as $inKey => $inVal) {
                            if ($inVal->type == 'file') {
                                if (request()->hasFile($inKey)) {
                                    try {
                                        $image = request()->file($inKey);
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
                                    'field_value' => strRandom(8),
                                    'type' => $inVal->type,
                                    'validation' => $inVal->validation,
                                ];
                            }
                        }
                        $gameSell['credential'] = $reqField;
                    } else {
                        $gameSell['credential'] = null;
                    }

                    $reqFieldSpecification = [];
                    if ($category->post_specification_form != null) {
                        foreach ($category->post_specification_form as $inKey => $inVal) {
                            if ($inVal->type == 'file') {
                                if (request()->hasFile($inKey)) {
                                    try {
                                        $image = request()->file($inKey);
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
                                    'field_value' => code(1),
                                    'type' => $inVal->type,
                                    'validation' => $inVal->validation,
                                ];
                            }

                        }

                        $gameSell['post_specification_form'] = $reqFieldSpecification;
                    } else {
                        $gameSell['post_specification_form'] = null;
                    }

                    $titleKey = array_rand(config('postTitle'));
                    $gameSell->title = config('postTitle')[$titleKey];
                    $gameSell->price = code(2);
                    $gameSell->details = $faker->text(600);
                    $gameSell->status = 1;


                    $directory = "assets/faker/posts/";
                    $imagesLocation = glob($directory . "*");
                    $imagesKey = array_rand($imagesLocation);

                    $getFileUploadable = str_replace($directory, '', $imagesLocation[$imagesKey]);
                    $fileArr = explode('.', $getFileUploadable);
                    $filename = 'demo_' . uniqid() . '.' . end($fileArr);

                    $pics[] = $filename;


                    File::copy($imagesLocation[$imagesKey], config('location.sellingPost.path') . $filename);

                    $gameSell->image = $pics;
                    $gameSell->save();
                    $pics = [];
                }

            });
        $this->info('status');
    }
}
