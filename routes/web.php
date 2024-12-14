<?php

use App\Models\Page;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CharterController;
use App\Http\Controllers\ShuttleController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MasterAreaController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\MasterSubAreaController;
use App\Http\Controllers\BookingCharterController;
use App\Http\Controllers\BookingShuttleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\MasterSpecialAreaController;
use App\Http\Controllers\DstController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomeController::class, 'index']);
Route::get('/page/{slug}', [WelcomeController::class, 'page']);
Route::get('/schedule', [WelcomeController::class, 'schedule']);
Route::get('/services', [WelcomeController::class, 'services']);
Route::get('/search', [WelcomeController::class, 'search'])->name('search');
Route::get('/booking', function () {
    $data = [
        'title'    => env('APP_NAME'),
        'app_name' => env('APP_NAME'),
        'pages'    => Page::get(),
    ];
    return view('schedule_not_found', $data);
});
Route::post('/booking', [WelcomeController::class, 'booking'])->name('booking');
Route::get('/booking/check', [WelcomeController::class, 'booking_check'])->name('booking_check');
Route::get('/booking/payment', [WelcomeController::class, 'booking_payment'])->name('booking.payment');
Route::post('/booking/process', [WelcomeController::class, 'booking_process'])->name('booking.process');
Route::post('/payment/venmo', [WelcomeController::class, 'payment_venmo'])->name('booking.payment_venmo');
Route::post('/payment/bank', [WelcomeController::class, 'payment_bank'])->name('booking.payment_bank');
Route::get('/booking/ticket', [WelcomeController::class, 'booking_ticket'])->name('booking_ticket');

Route::prefix('admin')->middleware('prevent-back-history')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'check']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware('admin');

    Route::get('/banner', [BannerController::class, 'index'])->name('admin.banner')->middleware('admin');
    Route::post('/banner', [BannerController::class, 'store'])->name('admin.banner.store')->middleware('admin');
    Route::get('/banner/edit/{id}', [BannerController::class, 'edit'])->name('admin.banner.edit')->middleware('admin');
    Route::put('/banner/update/{id}', [BannerController::class, 'update'])->name('admin.banner.update')->middleware('admin');
    Route::delete('/banner/delete/{id}', [BannerController::class, 'delete'])->name('admin.banner.delete')->middleware('admin');

    Route::get('/master_area', [MasterAreaController::class, 'index'])->name('admin.master_area')->middleware('admin');
    Route::post('/master_area', [MasterAreaController::class, 'store'])->name('admin.master_area.store')->middleware('admin');
    Route::get('/master_area/edit/{id}', [MasterAreaController::class, 'edit'])->name('admin.master_area.edit')->middleware('admin');
    Route::put('/master_area/update/{id}', [MasterAreaController::class, 'update'])->name('admin.master_area.update')->middleware('admin');
    Route::delete('/master_area/delete/{id}', [MasterAreaController::class, 'delete'])->name('admin.master_area.delete')->middleware('admin');

    Route::get('/master_sub_area', [MasterSubAreaController::class, 'index'])->name('admin.master_sub_area')->middleware('admin');
    Route::post('/master_sub_area', [MasterSubAreaController::class, 'store'])->name('admin.master_sub_area.store')->middleware('admin');
    Route::get('/master_sub_area/edit/{id}', [MasterSubAreaController::class, 'edit'])->name('admin.master_sub_area.edit')->middleware('admin');
    Route::put('/master_sub_area/update/{id}', [MasterSubAreaController::class, 'update'])->name('admin.master_sub_area.update')->middleware('admin');
    Route::delete('/master_sub_area/delete/{id}', [MasterSubAreaController::class, 'delete'])->name('admin.master_sub_area.delete')->middleware('admin');

    Route::get('/master_special_area', [MasterSpecialAreaController::class, 'index'])->name('admin.master_special_area')->middleware('admin');
    Route::post('/master_special_area', [MasterSpecialAreaController::class, 'store'])->name('admin.master_special_area.store')->middleware('admin');
    Route::get('/master_special_area/edit/{id}', [MasterSpecialAreaController::class, 'edit'])->name('admin.master_special_area.edit')->middleware('admin');
    Route::put('/master_special_area/update/{id}', [MasterSpecialAreaController::class, 'update'])->name('admin.master_special_area.update')->middleware('admin');
    Route::delete('/master_special_area/delete/{id}', [MasterSpecialAreaController::class, 'delete'])->name('admin.master_special_area.delete')->middleware('admin');

    Route::get('/pages', [PagesController::class, 'index'])->name('admin.pages')->middleware('admin');
    Route::get('/pages/add', [PagesController::class, 'add'])->name('admin.pages.add')->middleware('admin');
    Route::post('/pages/store', [PagesController::class, 'store'])->name('admin.pages.store')->middleware('admin');
    Route::get('/pages/edit/{id}', [PagesController::class, 'edit'])->name('admin.pages.edit')->middleware('admin');
    Route::put('/pages/update/{id}', [PagesController::class, 'update'])->name('admin.pages.update')->middleware('admin');
    Route::delete('/pages/delete/{id}', [PagesController::class, 'delete'])->name('admin.pages.delete')->middleware('admin');

    // Route::get('/agent', [AgentController::class, 'index'])->name('admin.agent')->middleware('admin');
    // Route::post('/agent', [AgentController::class, 'store'])->name('admin.agent.store')->middleware('admin');
    // Route::get('/agent/edit/{id}', [AgentController::class, 'edit'])->name('admin.agent.edit')->middleware('admin');
    // Route::put('/agent/update/{id}', [AgentController::class, 'update'])->name('admin.agent.update')->middleware('admin');
    // Route::delete('/agent/delete/{id}', [AgentController::class, 'delete'])->name('admin.agent.delete')->middleware('admin');

    Route::get('/voucher', [VoucherController::class, 'index'])->name('admin.voucher')->middleware('admin');
    Route::post('/voucher', [VoucherController::class, 'store'])->name('admin.voucher.store')->middleware('admin');
    Route::get('/voucher/edit/{id}', [VoucherController::class, 'edit'])->name('admin.voucher.edit')->middleware('admin');
    Route::put('/voucher/update/{id}', [VoucherController::class, 'update'])->name('admin.voucher.update')->middleware('admin');
    Route::delete('/voucher/delete/{id}', [VoucherController::class, 'delete'])->name('admin.voucher.delete')->middleware('admin');

    Route::get('/charter', [CharterController::class, 'index'])->name('admin.charter')->middleware('admin');
    Route::get('/charter/add', [CharterController::class, 'add'])->name('admin.charter.add')->middleware('admin');
    Route::post('/charter', [CharterController::class, 'store'])->name('admin.charter.store')->middleware('admin');
    Route::get('/charter/edit/{id}', [CharterController::class, 'edit'])->name('admin.charter.edit')->middleware('admin');
    Route::put('/charter/update/{id}', [CharterController::class, 'update'])->name('admin.charter.update')->middleware('admin');
    Route::delete('/charter/delete/{id}', [CharterController::class, 'delete'])->name('admin.charter.delete')->middleware('admin');

    Route::get('/shuttle', [ShuttleController::class, 'index'])->name('admin.shuttle')->middleware('admin');
    Route::get('/shuttle/add', [ShuttleController::class, 'add'])->name('admin.shuttle.add')->middleware('admin');
    Route::post('/shuttle', [ShuttleController::class, 'store'])->name('admin.shuttle.store')->middleware('admin');
    Route::get('/shuttle/edit/{id}', [ShuttleController::class, 'edit'])->name('admin.shuttle.edit')->middleware('admin');
    Route::put('/shuttle/update/{id}', [ShuttleController::class, 'update'])->name('admin.shuttle.update')->middleware('admin');
    Route::delete('/shuttle/delete/{id}', [ShuttleController::class, 'delete'])->name('admin.shuttle.delete')->middleware('admin');

    Route::get('/booking/shuttle', [BookingShuttleController::class, 'index'])->name('admin.booking.shuttle')->middleware('admin');
    Route::delete('/booking/shuttle/delete/{id}', [BookingShuttleController::class, 'delete'])->name('admin.booking.shuttle.delete')->middleware('admin');

    Route::get('/booking/charter', [BookingCharterController::class, 'index'])->name('admin.booking.charter')->middleware('admin');
    Route::delete('/booking/charter/delete/{id}', [BookingCharterController::class, 'delete'])->name('admin.booking.charter.delete')->middleware('admin');

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.admin')->middleware('admin');
    Route::post('/admin', [AdminController::class, 'store'])->name('admin.admin.store')->middleware('admin');
    Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.admin.edit')->middleware('admin');
    Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.admin.update')->middleware('admin');
    Route::post('/admin/reset_password/{id}', [AdminController::class, 'reset_password'])->name('admin.admin.reset_password')->middleware('admin');
    Route::delete('/admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.admin.delete')->middleware('admin');

    Route::get('/dst', [DstController::class, 'index'])->name('admin.dst')->middleware('admin');
    Route::get('/dst/edit/{id}', [DstController::class, 'edit'])->name('admin.dst.edit')->middleware('admin');
    Route::put('/dst/update/{id}', [DstController::class, 'update'])->name('admin.dst.update')->middleware('admin');

    Route::get('/eta', [DstController::class, 'index'])->name('admin.eta')->middleware('admin');
    Route::get('/eta/edit/{id}', [DstController::class, 'edit'])->name('admin.eta.edit')->middleware('admin');
    Route::put('/eta/update/{id}', [DstController::class, 'update'])->name('admin.eta.update')->middleware('admin');

    Route::get('/user', [UserController::class, 'index'])->name('admin.user')->middleware('admin');
    Route::post('/user', [UserController::class, 'store'])->name('admin.user.store')->middleware('admin');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit')->middleware('admin');
    Route::put('/user/update/{id}', [UserController::class, 'update'])->name('admin.user.update')->middleware('admin');
    Route::post('/user/reset_password/{id}', [UserController::class, 'reset_password'])->name('admin.user.reset_password')->middleware('admin');
    Route::delete('/user/delete/{id}', [UserController::class, 'delete'])->name('admin.user.delete')->middleware('admin');

    Route::resource('vehicle', \App\Http\Controllers\VehicleController::class, [
        'names' => [
            'index' => 'admin.vehicle.index',
            'show' => 'admin.vehicle.show',
            'edit' => 'admin.vehicle.edit',
            'create' => 'admin.vehicle.create',
            'store' => 'admin.vehicle.store',
            'update' => 'admin.vehicle.update',
            'destroy' => 'admin.vehicle.delete'
        ]
    ])->middleware('admin');

    Route::get('/bookingreportbyarea/export_xlx', [\App\Http\Controllers\BookingReportByAreaController::class, 'export_xlx'])->name('admin.bookingreportbyarea.export_xlx')->middleware('admin');
    Route::resource('bookingreportbyarea', \App\Http\Controllers\BookingReportByAreaController::class, [
        'names' => [
            'index' => 'admin.bookingreportbyarea.index',
            'show' => 'admin.bookingreportbyarea.show',
        ]
    ])->middleware('admin');


    Route::get('/bookingreportcharterbyarea/export_xlx', [\App\Http\Controllers\BookingReportCharterByAreaController::class, 'export_xlx'])->name('admin.bookingreportcharterbyarea.export_xlx')->middleware('admin');
    Route::resource('bookingreportcharterbyarea', \App\Http\Controllers\BookingReportCharterByAreaController::class, [
        'names' => [
            'index' => 'admin.bookingreportcharterbyarea.index',
            'show' => 'admin.bookingreportcharterbyarea.show',
        ]
    ])->middleware('admin');

    Route::resource('bookingreportbyvehicle', \App\Http\Controllers\BookingReportByVehicleController::class, [
        'names' => [
            'index' => 'admin.bookingreportbyvehicle.index',
            'show' => 'admin.bookingreportbyvehicle.show',
        ]
    ])->middleware('admin');

    Route::resource(
        'bookingreportcharterbyvehicle',
        \App\Http\Controllers\BookingReportCharterByVehicleController::class,
        [
            'names' => [
                'index' => 'admin.bookingreportcharterbyvehicle.index',
                'show' => 'admin.bookingreportcharterbyvehicle.show',
            ]
        ]
    )->middleware('admin');
});

Route::get('/expired_booking', [CronJobController::class, 'expired_booking']);
