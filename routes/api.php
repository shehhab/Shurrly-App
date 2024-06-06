<?php

use Pusher\Pusher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;


// Api routes for controllers Auth
use App\Http\Controllers\Payment\PaymentController;


// Api routes for controllers advisor
use App\Http\Controllers\Payment\PayPallController;
use App\Http\Controllers\Api\core\Chat\ChatController;
use App\Http\Controllers\Api\Seeker\Auth\SocialGoogle;
use App\Http\Controllers\Api\core\Block\BlockController;


// Api routes for controllers seeker

use App\Http\Controllers\Api\Seeker\Auth\LoginController;
use App\Http\Controllers\Api\core\Report\ReportController;
use App\Http\Controllers\Api\Seeker\Home\SearchController;
use App\Http\Controllers\Api\Seeker\Home\TopRateController;

// Api routes for controllers core

use App\Http\Controllers\Api\core\Chat\GetAllChatController;
use App\Http\Controllers\Api\Seeker\Auth\RegisterController;
use App\Http\Controllers\Api\Seeker\Home\SearchTestcontroller;
use App\Http\Controllers\Api\core\Support\GetSupportController;

use App\Http\Controllers\Api\Seeker\Auth\VerifyEmailController;
use App\Http\Controllers\Api\Seeker\Home\SpecialistsController;
use App\Http\Controllers\Api\Seeker\Rate\rate_AdviosrController;
use App\Http\Controllers\Api\Seeker\Rate\rate_productController;
use App\Http\Controllers\Api\core\GetData\GetDataSkillController;
use App\Http\Controllers\Api\Seeker\Home\SearchFillterController;
use App\Http\Controllers\Api\Seeker\Materials\MaterialController;
use App\Http\Controllers\Api\Seeker\Profile\GetProfileController;
use App\Http\Controllers\Api\Advisor\Product\AddProductController;
use App\Http\Controllers\Api\core\authantication\LogoutController;
use App\Http\Controllers\Api\Advisor\Session\SessionDataController;
use App\Http\Controllers\Api\Seeker\Session\DeletSessionController;
use App\Http\Controllers\Api\Advisor\Product\EditeProductController;
use App\Http\Controllers\Api\core\authantication\ValidOTPController;
use App\Http\Controllers\Api\Seeker\Materials\PageProductController;
use App\Http\Controllers\Api\Seeker\Profile\UpdateProfileController;
use App\Http\Controllers\Api\Advisor\Product\DeleteProductController;
use App\Http\Controllers\Api\Seeker\Session\SessionPendingController;
use App\Http\Controllers\Api\Advisor\Product\AllProductUserController;
use App\Http\Controllers\Api\Advisor\Session\SessionHistoryController;
use App\Http\Controllers\Api\Seeker\Session\SessionScheduleController;
use App\Http\Controllers\Api\Seeker\Session\UpcomingSessionController;
use App\Http\Controllers\Api\core\authantication\DeleteAccountController;
use App\Http\Controllers\Api\core\authantication\ResendOTPCodeController;
use App\Http\Controllers\Api\core\authantication\ResetPasswordController;
use App\Http\Controllers\Api\Seeker\Materials\ViewProductSavedController;
use App\Http\Controllers\Api\Seeker\Session\HideSessionHistoryController;
use App\Http\Controllers\Api\Advisor\Session\HideSeesionHistoryController;
use App\Http\Controllers\Api\core\authantication\ChangePasswordController;
use App\Http\Controllers\Api\core\authantication\ForgetPasswordController;
use App\Http\Controllers\Api\Seeker\Materials\ProductShowReviewController;
use App\Http\Controllers\Api\Advisor\authantication\LoginAdvisorController;
use App\Http\Controllers\Api\Seeker\Materials\UnSave_SaveProductController;
use App\Http\Controllers\Api\Advisor\authantication\CreateAdvisorController;
use App\Http\Controllers\Api\Advisor\Session\Accept_seesion_advisorController;
use App\Http\Controllers\Api\Advisor\Session\Cancell_seesion_advisorController;
use App\Http\Controllers\Api\Advisor\authantication\GetProfileAdvisorController;
use App\Http\Controllers\Api\Advisor\authantication\UpdateProfileAdvisorController;
use App\Http\Controllers\Api\Seeker\Session\SessionHistoryController as SessionSessionHistoryController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/
//Route::post('/test',[TestController::class,'store']);

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::post('/broadcasting/auth', function (Request $request) {
        $user = auth()->user(); // Get the authenticated user

        // $channelName = 'presence-chat.{1}.1.2';
        $channelName = $request->channelName;
        $socketId = $request->input('socket_id');

        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'encrypted' => true
            ]
        );

        $presenceData = [
            'user_id' => $user->id,
            'user_info' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ];

        $auth = $pusher->presence_auth($channelName, $socketId, $user->id, $presenceData);

        return response($auth);
    });
    Route::get('chats',[ChatController::class,'index']);
    Route::post('chat-send-message', [ChatController::class, 'sendMessage']);
    // Route::post('seeker-send-message', [ChatChatController::class, 'sendMessage']);
    // Route::post('advisor-send-message', [ChatAdvisorController::class, 'sendMessage']);
    Route::get('seeker', fn (Request $request) => $request->user())->name('seeker');;
});




//------------------------------------------Seeker-------------------------------------------------

//  API  routes seeker
Route::group(['prefix' => 'v1/seeker/'], function () {

    // ---------- auth  ----------- //

    Route::post('auth/register', RegisterController::class);
    Route::post('auth/login', LoginController::class);
    Route::post('auth/login/{provider}', [SocialGoogle::class, 'socialLogin']);

    // -----       matrial     --------- //
    Route::get('material', MaterialController::class);
    Route::get('material/product_page_review', ProductShowReviewController::class);


    // API routes for middleware seeker token authentication
    Route::group(['middleware' => 'auth:sanctum'], function () {

        //Route::apiResource('chat', ChatController::class)->only('index', 'store', 'show'); // !fot test not interested

        // ---------- auth  ----------- //

        Route::post('auth/verify_email', VerifyEmailController::class);
        Route::post('auth/update/profile', UpdateProfileController::class);
        Route::get('auth/get_profile', GetProfileController::class);

        // ---------- session  ----------- //

        Route::get('session/session_upcoming', UpcomingSessionController::class);
        Route::get('session/session_pending', SessionPendingController::class);
        Route::get('session/session_history', SessionSessionHistoryController::class);
        Route::post('session/session_delete', DeletSessionController::class);
        Route::post('session/session_book', SessionScheduleController::class);
        Route::post('session/session_hide', HideSessionHistoryController::class);


        // -----       matrial     --------- //
        Route::get('material/saved_products', ViewProductSavedController::class);
        Route::post('material/product_page', PageProductController::class);
        Route::post('material/Save_Product', UnSave_SaveProductController::class);



    });
});


//---------------------------------------------Advisor----------------------------------------------


//  API  routes advisor/auth
Route::group(['prefix' => 'v1/advisor'], function () {
    Route::post('/auth/login_advisor', LoginAdvisorController::class);
    Route::get('/auth/get_profile_advisor', GetProfileAdvisorController::class);
    Route::post('/product/add_products', AddProductController::class);


    // API routes for middleware advisor token authentication
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/auth/create_advisor', CreateAdvisorController::class);
        Route::post('/auth/update_profile_advisor', UpdateProfileAdvisorController::class);

        Route::get('/session/session_data', SessionDataController::class);
        Route::get('/session/session_history', SessionHistoryController::class);

        Route::post('/session/session_hide', HideSeesionHistoryController::class);

        Route::post('/session/accept_session', Accept_seesion_advisorController::class);
        Route::post('/session/cancell_session', Cancell_seesion_advisorController::class);

        Route::post('/product/edite_product', EditeProductController::class);

        Route::post('/product/delete_product', DeleteProductController::class);

        Route::get('/product/All_Product', AllProductUserController::class);

    });
});

//-----------------------------------------------Core--------------------------------------------

//  API  routes core/auth
Route::group(['prefix' => 'v1/core'], function () {
    Route::post('/auth/forget_password', ForgetPasswordController::class);
    Route::post('/auth/resend_otp', ResendOTPCodeController::class);
    Route::post('/auth/reset_password', ResetPasswordController::class);
    Route::post('/auth/check_otp', ValidOTPController::class);
    Route::post('/auth/get_support', GetSupportController::class);
    Route::get('/get_category_with_skills', GetDataSkillController::class);


    // API routes for middleware core token authentication
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/auth/change_password', ChangePasswordController::class);
        Route::post('/auth/delete_account', DeleteAccountController::class);
        Route::post('/auth/logout', LogoutController::class);
        Route::get('/auth/get_all_chat', GetAllChatController::class);
        Route::post('/auth/reports/send', ReportController::class);



    });
});




Route::group(['prefix' => 'v1/home'], function () {

    Route::get('/specialists_advisor', SpecialistsController::class);
    Route::get('/top_rate_advisor', TopRateController::class);

    Route::get('/search', SearchController::class);
    Route::get('/test', SearchTestcontroller::class);




    Route::post('/rate_product', rate_productController::class);
    Route::post('/rate_advisor', [rate_AdviosrController::class, 'rateAdvisor']);


    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::post('block/toggle', BlockController::class);


    });
});






Route::group(['prefix' => 'v1/Payment'], function () {

    Route::post('/',  [PaymentController::class, 'gerneratePaymentLink'])->name('payment');



    Route::group(['middleware' => 'auth:sanctum'], function () {

    });
});
