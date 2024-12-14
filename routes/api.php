<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\PagesController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\VoucherController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout');
    });
});

Route::get('/banner', [BannerController::class, 'index']);
Route::get('/banner/{id}', [BannerController::class, 'show']);

Route::get('/pages', [PagesController::class, 'index']);

Route::controller(BookingController::class)->group(function () {
    Route::get('/check_booking_number', 'check_booking_number');
    Route::get('/get_list_from_departure', 'get_list_from_departure');
    Route::get('/get_list_to_destination', 'get_list_to_destination');
    Route::get('/get_special_area', 'get_special_area');
    Route::post('/get_schedule_shuttles', 'get_schedule_shuttles');
    Route::post('/get_avail_charter', 'get_avail_charter');
    Route::post('/booking_history', 'show');
    Route::post('/booking_update', 'booking_update');
    Route::post('/booking', 'index');
    Route::get('/get_list_sub_area', 'get_list_sub_area');

    Route::middleware('auth:sanctum')->group(function () {
        //
    });

    Route::middleware('admin')->group(function () {
        Route::post('/booking/reschedule', 'reschedule');
        Route::post('/booking/change_payment_status', 'change_payment_status');
    });
});

Route::group(['prefix' => 'booking_filter'], function () {
    Route::post('/get_arrival_filter', [\App\Http\Controllers\API\BookingFilterController::class, 'get_arrival_filter']);
    Route::post('/get_departure_charter_filter', [\App\Http\Controllers\API\BookingFilterController::class, 'get_departure_charter_filter']);
    Route::post('/get_booking_schedule', [\App\Http\Controllers\API\BookingFilterController::class, 'get_booking_schedule']);
});

Route::controller(VoucherController::class)->group(function () {
    Route::get('/check_voucher', 'index');
});

Route::controller(ProfileController::class)->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', 'index');
        Route::post('/profile', 'update');
        Route::post('/update_password', 'update_password');
    });
});
