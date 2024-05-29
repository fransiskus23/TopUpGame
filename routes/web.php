<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear', function () {
    $output = new \Symfony\Component\Console\Output\BufferedOutput();
    Artisan::call('optimize:clear', array(), $output);
    return $output->fetch();
})->name('/clear');

Route::get('queue-work', function () {
    return Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

Route::get('cron', function () {
    Artisan::call('cron:run');
})->name('cron');



Route::get('/user', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/loginModal', 'Auth\LoginController@loginModal')->name('loginModal');

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['guest']], function () {
    Route::get('register/{sponsor?}', 'Auth\RegisterController@sponsor')->name('register.sponsor');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/check', 'User\VerificationController@check')->name('check');
    Route::get('/resend_code', 'User\VerificationController@resendCode')->name('resendCode');
    Route::post('/mail-verify', 'User\VerificationController@mailVerify')->name('mailVerify');
    Route::post('/sms-verify', 'User\VerificationController@smsVerify')->name('smsVerify');
    Route::post('twoFA-Verify', 'User\VerificationController@twoFAverify')->name('twoFA-Verify');
    Route::middleware('userCheck')->group(function () {

        Route::get('/dashboard', 'User\HomeController@index')->name('home');


        Route::middleware('module:top_up')->group(function () {
            Route::post('payment/topUp', 'User\TopUpController@topUpPayment')->name('topUp.payment');
            //top up order
            Route::get('/top-up/order', 'User\TopUpController@topUpOrder')->name('topUpOrder');
            Route::get('/top-up/search', 'User\TopUpController@topUpSearch')->name('topUpOrder.search');

        });

        Route::middleware('module:voucher')->group(function () {
            Route::post('payment/voucher', 'User\VoucherController@voucherPayment')->name('voucher.payment');
            //voucher order
            Route::get('/voucher/order', 'User\VoucherController@voucherOrder')->name('voucherOrder');
            Route::get('/voucher/search', 'User\VoucherController@voucherSearch')->name('voucherOrder.search');

        });


        Route::middleware('module:gift_card')->group(function () {
            Route::post('payment/gift-card', 'User\GiftCardController@giftCardPayment')->name('giftCard.payment');
            Route::get('/gift-card/order', 'User\GiftCardController@giftCardOrder')->name('giftCardOrder');
            Route::get('/gift-card/search', 'User\GiftCardController@giftCardSearch')->name('giftCardOrder.search');
        });

        Route::middleware('module:sell_post')->group(function () {

            Route::post('payment/sellPost', 'User\SellPostController@sellPostMakePayment')->name('sellPost.makePayment');
            Route::get('/post/order', 'User\SellPostController@sellPostOrder')->name('sellPostOrder');
            Route::get('/post/search', 'User\SellPostController@sellPostOrderSearch')->name('sellPostOrder.search');
            Route::get('/post/offer', 'User\SellPostController@sellPostOfferList')->name('sellPostOffer.List');
            Route::get('/post/my-offer', 'User\SellPostController@sellPostMyOffer')->name('sellPostMyOffer');

            //Sell Game
            Route::get('/sell-post/create', 'User\SellPostController@sellCreate')->name('sellCreate');
            Route::post('/sell-post/store', 'User\SellPostController@sellStore')->name('sellStore');
            Route::get('/sell-post/list', 'User\SellPostController@sellList')->name('sellList');
            Route::get('/sell-post/search', 'User\SellPostController@sellPostSearch')->name('sellPost.search');
            Route::get('/sell-post/edit/{id}', 'User\SellPostController@sellPostEdit')->name('sellPostEdit');
            Route::post('/sell-post/update/{id}', 'User\SellPostController@sellPostUpdate')->name('sellPostUpdate');
            Route::delete('/sell-post/delete/{id}', 'User\SellPostController@sellPostDelete')->name('sellPostDelete');
            Route::delete('/sell/image-delete/{id}/{imgDelete}', 'User\SellPostController@SellDelete')->name('sell.image.delete');
            Route::get('/post/my-offer-search', 'User\SellPostController@myOfferSearch')->name('myOffer.search');

            //Make Offer
            Route::post('/sell-post/offer', 'User\SellPostController@sellPostOffer')->name('sellPostOffer');
            Route::get('/sell-post/offerList', 'User\SellPostController@sellPostOfferMore')->name('sellPostOfferMore');
            Route::post('/sell-post/offer/remove', 'User\SellPostController@sellPostOfferRemove')->name('sellPostOfferRemove');
            Route::post('/sell-post/offer/reject', 'User\SellPostController@sellPostOfferReject')->name('sellPostOfferReject');
            Route::post('/sell-post/offer/accept', 'User\SellPostController@sellPostOfferAccept')->name('sellPostOfferAccept');
            Route::get('/sell-post/offer/chat/{uuId}', 'User\SellPostController@sellPostOfferChat')->name('offerChat');

            //Sell Post Payment Lock
            Route::post('/sell-post/offer/lock', 'User\SellPostController@sellPostOfferPaymentLock')->name('sellPostOfferPaymentLock');
            Route::post('/sell-post/payment/{sellPost}', 'User\SellPostController@sellPostPayment')->name('sellPost.payment');
            Route::get('/sell-post/payment/{sellPost:payment_uuid}', 'User\SellPostController@sellPostPaymentUrl')->name('sellPost.payment.url');
        });


        Route::get('add-fund', 'User\HomeController@addFund')->name('addFund');
        Route::post('add-fund', 'PaymentController@addFundRequest')->name('addFund.request');
        Route::get('paynow', 'PaymentController@depositConfirm')->name('addFund.confirm');
        Route::post('paynow', 'PaymentController@fromSubmit')->name('addFund.fromSubmit');


        //transaction
        Route::get('/transaction', 'User\HomeController@transaction')->name('transaction');
        Route::get('/transaction/search', 'User\HomeController@transactionSearch')->name('transaction.search');
        Route::get('payment-log', 'User\HomeController@fundHistory')->name('fund-history');
        Route::get('payment-log/search', 'User\HomeController@fundHistorySearch')->name('fund-history.search');


        Route::get('push-chat-show/{uuId}', 'ChatNotificationController@show')->name('push.chat.show');
        Route::post('push-chat-newMessage', 'ChatNotificationController@newMessage')->name('push.chat.newMessage');

        // TWO-FACTOR SECURITY
        Route::get('/twostep-security', 'User\HomeController@twoStepSecurity')->name('twostep.security');
        Route::post('twoStep-enable', 'User\HomeController@twoStepEnable')->name('twoStepEnable');
        Route::post('twoStep-disable', 'User\HomeController@twoStepDisable')->name('twoStepDisable');


        Route::get('push-notification-show', 'SiteNotificationController@show')->name('push.notification.show');
        Route::get('push.notification.readAll', 'SiteNotificationController@readAll')->name('push.notification.readAll');
        Route::get('push-notification-readAt/{id}', 'SiteNotificationController@readAt')->name('push.notification.readAt');


        Route::get('/payout', 'User\HomeController@payoutMoney')->name('payout.money');
        Route::post('/payout', 'User\HomeController@payoutMoneyRequest')->name('payout.moneyRequest');
        Route::get('/payout/preview', 'User\HomeController@payoutPreview')->name('payout.preview');
        Route::post('/payout/preview', 'User\HomeController@payoutRequestSubmit')->name('payout.submit');


        Route::get('payout-log', 'User\HomeController@payoutHistory')->name('payout.history');
        Route::get('payout-log/search', 'User\HomeController@payoutHistorySearch')->name('payout.history.search');


        Route::get('/profile', 'User\HomeController@profile')->name('profile');
        Route::post('/updateProfile', 'User\HomeController@updateProfile')->name('updateProfile');
        Route::put('/updateInformation', 'User\HomeController@updateInformation')->name('updateInformation');
        Route::post('/updatePassword', 'User\HomeController@updatePassword')->name('updatePassword');
        Route::post('/verificationSubmit', 'User\HomeController@verificationSubmit')->name('verificationSubmit');
        Route::post('/addressVerification', 'User\HomeController@addressVerification')->name('addressVerification');


        Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
            Route::get('/', 'User\SupportController@index')->name('list');
            Route::get('/create', 'User\SupportController@create')->name('create');
            Route::post('/create', 'User\SupportController@store')->name('store');
            Route::get('/view/{ticket}', 'User\SupportController@ticketView')->name('view');
            Route::put('/reply/{ticket}', 'User\SupportController@reply')->name('reply');
            Route::get('/download/{ticket}', 'User\SupportController@download')->name('download');
        });


    });
});


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', 'Admin\LoginController@showLoginForm')->name('login');


    Route::post('/', 'Admin\LoginController@login')->name('login');
    Route::post('/logout', 'Admin\LoginController@logout')->name('logout');


    Route::get('/password/reset', 'Admin\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/email', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset/{token}', 'Admin\Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/password/reset', 'Admin\Auth\ResetPasswordController@reset')->name('password.update');


    Route::get('/403', 'Admin\DashboardController@forbidden')->name('403');

    Route::group(['middleware' => ['auth:admin',]], function () {
        Route::get('/dashboard', 'Admin\DashboardController@dashboard')->name('dashboard');


        Route::middleware('module:top_up')->group(function () {
            Route::get('/topUp-list', 'Admin\CategoryController@category')->name('category');
            Route::get('/topUp/create', 'Admin\CategoryController@categoryCreate')->name('categoryCreate');
            Route::post('/topUp/store/{language?}', 'Admin\CategoryController@categoryStore')->name('categoryStore');
            Route::delete('/topUp/delete/{id}', 'Admin\CategoryController@categoryDelete')->name('categoryDelete');
            Route::get('/topUp/edit/{id}', 'Admin\CategoryController@categoryEdit')->name('categoryEdit');
            Route::put('/topUp/update/{id}/{language?}', 'Admin\CategoryController@categoryUpdate')->name('categoryUpdate');
            Route::delete('/topUp/image-delete/{id}', 'Admin\CategoryController@imageDelete')->name('top.image.delete');


            /*====== Manage Game Service =======*/
            Route::get('/topUp-list/services/{id}', 'Admin\CategoryController@gameServices')->name('gameList.services');
            Route::post('/topUp-list/services/active', 'Admin\CategoryController@activeMultiple')->name('gameList.services.active');
            Route::post('/topUp-list/services/inactive', 'Admin\CategoryController@inactiveMultiple')->name('gameList.services.inactive');
            Route::post('/topUp-list/service/store/{id}', 'Admin\CategoryController@gameServicesStore')->name('gameServicesStore');
            Route::post('/topUp-list/service/edit/{id}', 'Admin\CategoryController@gameServicesEdit')->name('gameServicesEdit');
            Route::delete('/topUp-list/service/edit/{id}', 'Admin\CategoryController@gameServicesDelete')->name('gameServicesDelete');
            Route::post('/topUp-list/service/bulkUpload/{id}', 'Admin\CategoryController@uploadBulkGameCode')->name('uploadBulkgameList');
            Route::get('/topUp-list/service/sampleFiles', 'Admin\CategoryController@gameSampleFiles')->name('gameSampleFiles');

            //sell summary
            Route::get('/topup/payment/{userId?}', 'Admin\LogController@topUpSellTran')->name('topUpSell');
            Route::get('/topup/payment/search', 'Admin\LogController@topUpSellSearch')->name('topUpSell.search');

        });

        Route::middleware('module:voucher')->group(function () {
            /*====== Manage Game Voucher =======*/
            Route::get('/voucher', 'Admin\GameVoucherController@voucherList')->name('gameVoucher');
            Route::get('/voucher/create', 'Admin\GameVoucherController@gameVoucherCreate')->name('gameVoucherCreate');
            Route::post('/voucher/store/{language?}', 'Admin\GameVoucherController@gameVoucherStore')->name('gameVoucherStore');
            Route::delete('/voucher/delete/{id}', 'Admin\GameVoucherController@gameVoucherDelete')->name('gameVoucherDelete');
            Route::get('/voucher/edit/{id}', 'Admin\GameVoucherController@gameVoucherEdit')->name('gameVoucherEdit');
            Route::put('/voucher/update/{id}/{language?}', 'Admin\GameVoucherController@gameVoucherUpdate')->name('gameVoucherUpdate');

            /*====== Manage Voucher Service =======*/
            Route::post('/voucher/service/store', 'Admin\GameVoucherController@voucherServicesStore')->name('voucherServicesStore');
            Route::put('/voucher/service/update/{id}', 'Admin\GameVoucherController@voucherServiceUpdate')->name('voucherServiceUpdate');

            Route::get('/voucher/service/{serviceId?}/list', 'Admin\GameVoucherController@voucherServiceList')->name('gameVoucher.serviceCode');
            Route::post('/voucher/service/code/store/{voucherId?}/{serviceId?}', 'Admin\GameVoucherController@voucherServiceCodeStore')->name('voucherServiceCodeStore');
            Route::put('/voucher/service/code/update/{id}', 'Admin\GameVoucherController@voucherServiceCodeUpdate')->name('voucherServiceCodeUpdate');
            Route::delete('/voucher/service/code/delete/{id}', 'Admin\GameVoucherController@voucherServiceCodeDelete')->name('voucherServiceCodeDelete');
            Route::post('/voucher/service/bulkUpload', 'Admin\GameVoucherController@uploadBulkVoucherCode')->name('uploadBulkVoucherCode');
            Route::get('/voucher/service/sampleFiles', 'Admin\GameVoucherController@sampleFiles')->name('VouchersampleFiles');

            //sell summary
            Route::get('/voucher/payment/{userId?}', 'Admin\LogController@voucherSellTran')->name('voucherSell');
            Route::get('/voucher/payment/search', 'Admin\LogController@voucherSellSearch')->name('voucherSell.search');

        });

        Route::middleware('module:gift_card')->group(function () {
            /*====== Manage Gift Card =======*/
            Route::get('/gift-card', 'Admin\GiftCardController@giftCardList')->name('giftCard');
            Route::get('/gift-card/create', 'Admin\GiftCardController@giftCardCreate')->name('giftCardCreate');
            Route::post('/gift-card/store/{language?}', 'Admin\GiftCardController@giftCardStore')->name('giftCardStore');
            Route::delete('/gift-card/delete/{id}', 'Admin\GiftCardController@giftCardDelete')->name('giftCardDelete');
            Route::get('/gift-card/edit/{id}', 'Admin\GiftCardController@giftCardEdit')->name('giftCardEdit');
            Route::put('/gift-card/update/{id}/{language?}', 'Admin\GiftCardController@giftCardUpdate')->name('giftCardUpdate');

            /*====== Manage Gift Card Service =======*/
            Route::post('/gift-card/service/store', 'Admin\GiftCardController@giftCardServicesStore')->name('giftCardServicesStore');
            Route::put('/gift-card/service/update/{id}', 'Admin\GiftCardController@giftCardServiceUpdate')->name('giftCardServiceUpdate');

            Route::get('/gift-card/service/{serviceId?}/list', 'Admin\GiftCardController@giftCardServiceList')->name('giftCard.serviceCode');
            Route::post('/gift-card/service/code/store/{voucherId?}/{serviceId?}', 'Admin\GiftCardController@giftCardServiceCodeStore')->name('giftCardServiceCodeStore');
            Route::put('/gift-card/service/code/update/{id}', 'Admin\GiftCardController@giftCardServiceCodeUpdate')->name('giftCardServiceCodeUpdate');
            Route::delete('/gift-card/service/code/delete/{id}', 'Admin\GiftCardController@giftCardServiceCodeDelete')->name('giftCardServiceCodeDelete');
            Route::post('/gift-card/service/bulkUpload', 'Admin\GiftCardController@uploadBulkGiftCardCode')->name('uploadBulkGiftCardCode');
            Route::get('/gift-card/service/sampleFiles', 'Admin\GiftCardController@sampleFiles')->name('sampleFiles');


            //sell summary
            Route::get('/gift-card/payment/{userId?}', 'Admin\LogController@giftCardSellTran')->name('sellGiftCard');
            Route::get('/gift-card/payment/search', 'Admin\LogController@giftCardSellSearch')->name('giftCardSell.search');
        });

        Route::middleware('module:sell_post')->group(function () {

            /*====== Manage Sell Post Category =======*/
            Route::get('/sell/category-list', 'Admin\SellPostCategoryController@category')->name('sellPostCategory');
            Route::get('/sell/create', 'Admin\SellPostCategoryController@categoryCreate')->name('sellPostCategoryCreate');
            Route::post('/sell/store/{language?}', 'Admin\SellPostCategoryController@categoryStore')->name('sellPostCategoryStore');
            Route::delete('/sell/delete/{id}', 'Admin\SellPostCategoryController@categoryDelete')->name('sellPostCategoryDelete');
            Route::get('/sell/edit/{id}', 'Admin\SellPostCategoryController@categoryEdit')->name('sellPostCategoryEdit');
            Route::put('/sell/update/{id}/{language?}', 'Admin\SellPostCategoryController@categoryUpdate')->name('sellPostCategoryUpdate');


            /*====== Manage Sell Post List =======*/
            Route::get('/sell/list/{status?}/{user_id?}', 'Admin\SellPostCategoryController@sellList')->name('gameSellList');
            Route::get('/sell/search', 'Admin\SellPostCategoryController@sellSearch')->name('sell.search');
            Route::get('/sell/details/{id}', 'Admin\SellPostCategoryController@sellDetails')->name('sell.details');
            Route::put('/sell/details/{id}', 'Admin\SellPostCategoryController@SellUpdate')->name('sell.update');
            Route::delete('/sell/image-delete/{id}/{imgDelete}', 'Admin\SellPostCategoryController@SellDelete')->name('sell.image.delete');
            Route::post('/sell/action/', 'Admin\SellPostCategoryController@SellAction')->name('sellPostAction');
            Route::get('/sell/offer/{sellPostId}', 'Admin\SellPostCategoryController@sellPostOffer')->name('sellPost.offer');
            Route::get('/sell/conversation/{uuid}', 'Admin\SellPostCategoryController@conversation')->name('sellPost.conversation');


            Route::get('/post/sell', 'Admin\LogController@postSellTran')->name('postSell');
            Route::get('/post/sell/search', 'Admin\LogController@postSellSearch')->name('postSell.search');
            Route::get('/post/sell/paymentRelease', 'Admin\LogController@paymentRelease')->name('postSell.paymentRelease');
            Route::get('/post/sell/paymentUpcoming', 'Admin\LogController@paymentUpcoming')->name('postSell.paymentUpcoming');

            Route::post('/post/sell/payment-hold', 'Admin\LogController@paymentHold')->name('paymentHold');
            Route::post('/post/sell/payment-unhold', 'Admin\LogController@paymentUnhold')->name('paymentUnhold');

            Route::get('/sell/offer-list/{userId?}', 'Admin\SellPostCategoryController@sellOffer')->name('sellOffer');

        });

        //Top Up
        Route::post('/topUp/active', 'Admin\CategoryController@activeGameMultiple')->name('gameList.active');
        Route::post('/topUp/inactive', 'Admin\CategoryController@inactiveGameMultiple')->name('gameList.inactive');

        //Voucher
        Route::post('/voucher/active', 'Admin\GameVoucherController@activeMultiple')->name('gameVoucher.active');
        Route::post('/voucher/inactive', 'Admin\GameVoucherController@inactiveMultiple')->name('gameVoucher.inactive');
        Route::post('/voucher/service/code/active', 'Admin\GameVoucherController@voucherServiceCodeActiveMultiple')->name('voucherServiceCode.active');
        Route::post('/voucher/service/code/inactive', 'Admin\GameVoucherController@voucherServiceCodeInactiveMultiple')->name('voucherServiceCode.inactive');
        Route::post('/voucher/service/codes/delete', 'Admin\GameVoucherController@voucherServiceCodeDeleteMultiple')->name('voucherServiceCode.delete');

        //Gift Card
        Route::post('/gift-card/active', 'Admin\GiftCardController@activeMultiple')->name('giftCard.active');
        Route::post('/gift-card/inactive', 'Admin\GiftCardController@inactiveMultiple')->name('giftCard.inactive');
        Route::post('/gift-card/service/code/active', 'Admin\GiftCardController@giftCardServiceCodeActiveMultiple')->name('giftCardServiceCode.active');
        Route::post('/gift-card/service/code/inactive', 'Admin\GiftCardController@giftCardServiceCodeInactiveMultiple')->name('giftCardServiceCode.inactive');
        Route::post('/gift-card/service/codes/delete', 'Admin\GiftCardController@giftCardServiceCodeDeleteMultiple')->name('giftCardServiceCode.delete');

        //Sell Post
        Route::post('/sell-post/active', 'Admin\SellPostCategoryController@activeGameMultiple')->name('sellPost.active');
        Route::post('/sell-post/inactive', 'Admin\SellPostCategoryController@inactiveGameMultiple')->name('sellPost.inactive');

        Route::post('/post/sell/hold', 'Admin\LogController@holdMultiple')->name('holdMultiple');
        Route::post('/post/sell/release', 'Admin\LogController@releaseMultiple')->name('releaseMultiple');

        Route::get('push-chat-show/{uuId}', 'ChatNotificationController@showByAdmin')->name('push.chat.show');
        Route::post('push-chat-newMessage', 'ChatNotificationController@newMessageByAdmin')->name('push.chat.newMessage');

        Route::get('/profile', 'Admin\DashboardController@profile')->name('profile');
        Route::put('/profile', 'Admin\DashboardController@profileUpdate')->name('profileUpdate');
        Route::get('/password', 'Admin\DashboardController@password')->name('password');
        Route::put('/password', 'Admin\DashboardController@passwordUpdate')->name('passwordUpdate');

        Route::get('/identity-form', 'Admin\IdentyVerifyFromController@index')->name('identify-form');
        Route::post('/identity-form', 'Admin\IdentyVerifyFromController@store')->name('identify-form.store');
        Route::post('/identity-form/action', 'Admin\IdentyVerifyFromController@action')->name('identify-form.action');


        /* ====== Transaction Log =====*/
        Route::get('/transaction', 'Admin\LogController@transaction')->name('transaction');
        Route::get('/transaction-search', 'Admin\LogController@transactionSearch')->name('transaction.search');

        //sell summary
        Route::put('/top-up-sell/action/{id}', 'Admin\LogController@topUpSellAction')->name('topUpSell.action');
        Route::put('/voucher-sell/action/{id}', 'Admin\LogController@voucherSellAction')->name('voucherSell.action');
        Route::put('/gift-card/sell/action/{id}', 'Admin\LogController@giftCardSellAction')->name('giftCardSell.action');


        /*====Manage Users ====*/
        Route::get('/users', 'Admin\UsersController@index')->name('users');
        Route::get('/users/search', 'Admin\UsersController@search')->name('users.search');
        Route::post('/users-active', 'Admin\UsersController@activeMultiple')->name('user-multiple-active');
        Route::post('/users-inactive', 'Admin\UsersController@inactiveMultiple')->name('user-multiple-inactive');
        Route::get('/user/edit/{id}', 'Admin\UsersController@userEdit')->name('user-edit');
        Route::post('/user/update/{id}', 'Admin\UsersController@userUpdate')->name('user-update');
        Route::post('/user/password/{id}', 'Admin\UsersController@passwordUpdate')->name('userPasswordUpdate');
        Route::post('/user/balance-update/{id}', 'Admin\UsersController@userBalanceUpdate')->name('user-balance-update');

        Route::get('/user/send-email/{id}', 'Admin\UsersController@sendEmail')->name('send-email');
        Route::post('/user/send-email/{id}', 'Admin\UsersController@sendMailUser')->name('user.email-send');
        Route::get('/user/transaction/{id}', 'Admin\UsersController@transaction')->name('user.transaction');
        Route::get('/user/fundLog/{id}', 'Admin\UsersController@funds')->name('user.fundLog');
        Route::get('/user/payoutLog/{id}', 'Admin\UsersController@payoutLog')->name('user.withdrawal');
        Route::get('/user/referralMember/{id}', 'Admin\UsersController@referralMember')->name('user.referralMember');
        Route::post('/user/login', 'Admin\UsersController@userLogin')->name('userLogin');
        Route::get('users/kyc/pending', 'Admin\UsersController@kycPendingList')->name('kyc.users.pending');
        Route::get('users/kyc', 'Admin\UsersController@kycList')->name('kyc.users');
        Route::put('users/kycAction/{id}', 'Admin\UsersController@kycAction')->name('users.Kyc.action');
        Route::get('user/{user}/kyc', 'Admin\UsersController@userKycHistory')->name('user.userKycHistory');

        Route::get('/email-send', 'Admin\UsersController@emailToUsers')->name('email-send');
        Route::post('/email-send', 'Admin\UsersController@sendEmailToUsers')->name('email-send.store');


        /*=====Payment Log=====*/
        Route::get('payment-methods', 'Admin\PaymentMethodController@index')->name('payment.methods');
        Route::post('payment-methods/deactivate', 'Admin\PaymentMethodController@deactivate')->name('payment.methods.deactivate');
        Route::get('payment-methods/deactivate', 'Admin\PaymentMethodController@deactivate')->name('payment.methods.deactivate');
        Route::post('sort-payment-methods', 'Admin\PaymentMethodController@sortPaymentMethods')->name('sort.payment.methods');
        Route::get('payment-methods/edit/{id}', 'Admin\PaymentMethodController@edit')->name('edit.payment.methods');
        Route::put('payment-methods/update/{id}', 'Admin\PaymentMethodController@update')->name('update.payment.methods');


        // Manual Methods
        Route::get('payment-methods/manual', 'Admin\ManualGatewayController@index')->name('deposit.manual.index');
        Route::get('payment-methods/manual/new', 'Admin\ManualGatewayController@create')->name('deposit.manual.create');
        Route::post('payment-methods/manual/new', 'Admin\ManualGatewayController@store')->name('deposit.manual.store');
        Route::get('payment-methods/manual/edit/{id}', 'Admin\ManualGatewayController@edit')->name('deposit.manual.edit');
        Route::put('payment-methods/manual/update/{id}', 'Admin\ManualGatewayController@update')->name('deposit.manual.update');


        Route::get('payment/pending', 'Admin\PaymentLogController@pending')->name('payment.pending');
        Route::put('payment/action/{id}', 'Admin\PaymentLogController@action')->name('payment.action');
        Route::get('payment/log', 'Admin\PaymentLogController@index')->name('payment.log');
        Route::get('payment/search', 'Admin\PaymentLogController@search')->name('payment.search');


        /*==========Payout Settings============*/
        Route::get('/payout-method', 'Admin\PayoutGatewayController@index')->name('payout-method');
        Route::get('/payout-method/create', 'Admin\PayoutGatewayController@create')->name('payout-method.create');
        Route::post('/payout-method/create', 'Admin\PayoutGatewayController@store')->name('payout-method.store');
        Route::get('/payout-method/{id}', 'Admin\PayoutGatewayController@edit')->name('payout-method.edit');
        Route::put('/payout-method/{id}', 'Admin\PayoutGatewayController@update')->name('payout-method.update');

        Route::get('/payout-log', 'Admin\PayoutRecordController@index')->name('payout-log');
        Route::get('/payout-log/search', 'Admin\PayoutRecordController@search')->name('payout-log.search');
        Route::get('/payout-request', 'Admin\PayoutRecordController@request')->name('payout-request');
        Route::put('/payout-action/{id}', 'Admin\PayoutRecordController@action')->name('payout-action');


        /* ===== Support Ticket ====*/
        Route::get('tickets/{status?}', 'Admin\TicketController@tickets')->name('ticket');
        Route::get('tickets/view/{id}', 'Admin\TicketController@ticketReply')->name('ticket.view');
        Route::put('ticket/reply/{id}', 'Admin\TicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'Admin\TicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'Admin\TicketController@ticketDelete')->name('ticket.delete');

        /* ===== Subscriber =====*/
        Route::get('subscriber', 'Admin\SubscriberController@index')->name('subscriber.index');
        Route::post('subscriber/remove', 'Admin\SubscriberController@remove')->name('subscriber.remove');
        Route::get('subscriber/send-email', 'Admin\SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/send-email', 'Admin\SubscriberController@sendEmail')->name('subscriber.mail');


        /* ===== website controls =====*/
        Route::any('/basic-controls', 'Admin\BasicController@index')->name('basic-controls');
        Route::post('/basic-controls', 'Admin\BasicController@updateConfigure')->name('basic-controls.update');

        /* ====== Plugin =====*/
        Route::get('/plugin-config', 'Admin\BasicController@pluginConfig')->name('plugin.config');
        Route::match(['get', 'post'], 'tawk-config', 'Admin\BasicController@tawkConfig')->name('tawk.control');
        Route::match(['get', 'post'], 'fb-messenger-config', 'Admin\BasicController@fbMessengerConfig')->name('fb.messenger.control');
        Route::match(['get', 'post'], 'google-recaptcha', 'Admin\BasicController@googleRecaptchaConfig')->name('google.recaptcha.control');
        Route::match(['get', 'post'], 'google-analytics', 'Admin\BasicController@googleAnalyticsConfig')->name('google.analytics.control');
        Route::match(['get', 'post'], 'currency-exchange-api-config', 'Admin\BasicController@currencyExchangeApiConfig')->name('currency.exchange.api.config');


        Route::any('/email-controls', 'Admin\EmailTemplateController@emailControl')->name('email-controls');
        Route::post('/email-controls', 'Admin\EmailTemplateController@emailConfigure')->name('email-controls.update');
        Route::post('/email-controls/action', 'Admin\EmailTemplateController@emailControlAction')->name('email-controls.action');
        Route::post('/email/test', 'Admin\EmailTemplateController@testEmail')->name('testEmail');


        Route::get('/email-template', 'Admin\EmailTemplateController@show')->name('email-template.show');
        Route::get('/email-template/edit/{id}', 'Admin\EmailTemplateController@edit')->name('email-template.edit');
        Route::post('/email-template/update/{id}', 'Admin\EmailTemplateController@update')->name('email-template.update');

        /*========Sms control ========*/
        Route::match(['get', 'post'], '/sms-controls', 'Admin\SmsTemplateController@smsConfig')->name('sms.config');
        Route::post('/sms-controls/action', 'Admin\SmsTemplateController@smsControlAction')->name('sms-controls.action');
        Route::get('/sms-template', 'Admin\SmsTemplateController@show')->name('sms-template');
        Route::get('/sms-template/edit/{id}', 'Admin\SmsTemplateController@edit')->name('sms-template.edit');
        Route::post('/sms-template/update/{id}', 'Admin\SmsTemplateController@update')->name('sms-template.update');

        Route::get('/notify-config', 'Admin\NotifyController@notifyConfig')->name('notify-config');
        Route::post('/notify-config', 'Admin\NotifyController@notifyConfigUpdate')->name('notify-config.update');
        Route::get('/notify-template', 'Admin\NotifyController@show')->name('notify-template.show');
        Route::get('/notify-template/edit/{id}', 'Admin\NotifyController@edit')->name('notify-template.edit');
        Route::post('/notify-template/update/{id}', 'Admin\NotifyController@update')->name('notify-template.update');


        /* ===== ADMIN Language SETTINGS ===== */
        Route::get('language', 'Admin\LanguageController@index')->name('language.index');
        Route::get('language/create', 'Admin\LanguageController@create')->name('language.create');
        Route::post('language/create', 'Admin\LanguageController@store')->name('language.store');
        Route::get('language/{language}', 'Admin\LanguageController@edit')->name('language.edit');
        Route::put('language/{language}', 'Admin\LanguageController@update')->name('language.update');
        Route::delete('language/{language}', 'Admin\LanguageController@delete')->name('language.delete');
        Route::get('/language/keyword/{id}', 'Admin\LanguageController@keywordEdit')->name('language.keywordEdit');
        Route::put('/language/keyword/{id}', 'Admin\LanguageController@keywordUpdate')->name('language.keywordUpdate');
        Route::post('/language/importJson', 'Admin\LanguageController@importJson')->name('language.importJson');
        Route::post('store-key/{id}', 'Admin\LanguageController@storeKey')->name('language.storeKey');
        Route::put('update-key/{id}', 'Admin\LanguageController@updateKey')->name('language.updateKey');
        Route::delete('delete-key/{id}', 'Admin\LanguageController@deleteKey')->name('language.deleteKey');


        Route::get('/logo-seo', 'Admin\BasicController@logoSeo')->name('logo-seo');
        Route::put('/logoUpdate', 'Admin\BasicController@logoUpdate')->name('logoUpdate');
        Route::put('/seoUpdate', 'Admin\BasicController@seoUpdate')->name('seoUpdate');
        Route::get('/breadcrumb', 'Admin\BasicController@breadcrumb')->name('breadcrumb');
        Route::put('/breadcrumb', 'Admin\BasicController@breadcrumbUpdate')->name('breadcrumbUpdate');


        /* ===== ADMIN TEMPLATE SETTINGS ===== */
        Route::get('template/{section}', 'Admin\TemplateController@show')->name('template.show');
        Route::put('template/{section}/{language}', 'Admin\TemplateController@update')->name('template.update');
        Route::get('contents/{content}', 'Admin\ContentController@index')->name('content.index');
        Route::get('content-create/{content}', 'Admin\ContentController@create')->name('content.create');
        Route::put('content-create/{content}/{language?}', 'Admin\ContentController@store')->name('content.store');
        Route::get('content-show/{content}/{name?}', 'Admin\ContentController@show')->name('content.show');
        Route::put('content-update/{content}/{language?}', 'Admin\ContentController@update')->name('content.update');
        Route::delete('contents/{id}', 'Admin\ContentController@contentDelete')->name('content.delete');


        Route::get('push-notification-show', 'SiteNotificationController@showByAdmin')->name('push.notification.show');
        Route::get('push.notification.readAll', 'SiteNotificationController@readAllByAdmin')->name('push.notification.readAll');
        Route::get('push-notification-readAt/{id}', 'SiteNotificationController@readAt')->name('push.notification.readAt');
        Route::match(['get', 'post'], 'pusher-config', 'SiteNotificationController@pusherConfig')->name('pusher.config');
    });


});


Route::match(['get', 'post'], 'success', 'PaymentController@success')->name('success');
Route::match(['get', 'post'], 'failed', 'PaymentController@failed')->name('failed');
Route::match(['get', 'post'], 'payment/{code}/{trx?}/{type?}', 'PaymentController@gatewayIpn')->name('ipn');


Route::get('/language/{code?}', 'FrontendController@language')->name('language');


Route::get('/blog/details/{slug}/{id}', 'FrontendController@blogDetails')->name('blogDetails');
Route::get('/blog', 'FrontendController@blog')->name('blog');

Route::get('/', 'FrontendController@index')->name('home');
Route::get('/about', 'FrontendController@about')->name('about');
Route::get('/faq', 'FrontendController@faq')->name('faq');
Route::get('/pricing', 'FrontendController@pricing')->name('pricing');

Route::get('/topUp/details/{slug?}/{id}', 'FrontendController@topUpDetails')->name('topUp.details')->middleware('module:top_up');
Route::get('/voucher/details/{slug?}/{id}', 'FrontendController@voucherDetails')->name('voucher.details')->middleware('module:voucher');
Route::get('/gift-card/details/{slug?}/{id}', 'FrontendController@giftCardDetails')->name('giftCard.details')->middleware('module:gift_card');
Route::get('/sell/post/{slug?}/{id}', 'FrontendController@sellPostList')->name('sellPost.list')->middleware('module:sell_post');
Route::get('/sell/post/details/{slug?}/{id}', 'FrontendController@sellPostDetails')->name('sellPost.details')->middleware('module:sell_post');

Route::get('/contact', 'FrontendController@contact')->name('contact');
Route::post('/contact', 'FrontendController@contactSend')->name('contact.send');

Route::post('/subscribe', 'FrontendController@subscribe')->name('subscribe');

Route::get('/shop', 'FrontendController@shop')->name('shop');
Route::get('/buy', 'FrontendController@buy')->name('buy');

Route::get('/{getLink}/{content_id}', 'FrontendController@getLink')->name('getLink');
Route::post('/ajaxCheckTopUpCalc', 'FrontendController@ajaxCheckTopUpCalc')->name('ajaxCheckTopUpCalc');
Route::post('/ajaxCheckVoucherCalc', 'FrontendController@ajaxCheckVoucherCalc')->name('ajaxCheckVoucherCalc');
Route::post('/ajaxCheckGiftCardCalc', 'FrontendController@ajaxCheckGiftCardCalc')->name('ajaxCheckGiftCardCalc');
Route::post('/ajaxCheckSellPostCalc', 'FrontendController@ajaxCheckSellPostCalc')->name('ajaxCheckSellPostCalc');




