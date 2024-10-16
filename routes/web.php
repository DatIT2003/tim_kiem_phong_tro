<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MotelController;
use App\Http\Controllers\PaymentController;
use App\District;
use App\Categories;
use App\Motelroom;

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

// Homepage
Route::get('/', function () {
    $district = District::all();
    $categories = Categories::all();
    $hot_motelroom = Motelroom::where('approve', 1)->limit(6)->orderBy('count_view', 'desc')->get();
    $map_motelroom = Motelroom::where('approve', 1)->get();
    $listmotelroom = Motelroom::where('approve', 1)->paginate(4);
    return view('home.index', [
        'district' => $district,
        'categories' => $categories,
        'hot_motelroom' => $hot_motelroom,
        'map_motelroom' => $map_motelroom,
        'listmotelroom' => $listmotelroom
    ]);
});

// Category route
Route::get('category/{id}', [MotelController::class, 'getMotelByCategoryId']);

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminController::class, 'getLogin']);
    Route::post('login', [AdminController::class, 'postLogin'])->name('admin.login');
    
    Route::middleware('adminmiddleware')->group(function () {
        Route::get('logout', [AdminController::class, 'logout']);
        Route::get('', [AdminController::class, 'getIndex']);
        Route::get('thongke', [AdminController::class, 'getThongke']);
        Route::get('report', [AdminController::class, 'getReport']);
        
        Route::prefix('users')->group(function () {
            Route::get('list', [AdminController::class, 'getListUser']);
            Route::get('edit/{id}', [AdminController::class, 'getUpdateUser']);
            Route::post('edit/{id}', [AdminController::class, 'postUpdateUser'])->name('admin.user.edit');
            Route::get('del/{id}', [AdminController::class, 'DeleteUser']);
        });

        Route::prefix('motelrooms')->group(function () {
            Route::get('list', [AdminController::class, 'getListMotel']);
            Route::get('approve/{id}', [AdminController::class, 'ApproveMotelroom']);
            Route::get('unapprove/{id}', [AdminController::class, 'UnApproveMotelroom']);
            Route::get('del/{id}', [AdminController::class, 'DelMotelroom']);
        });
    });
});

// Motel room detail route
Route::get('/phongtro/{slug}', function ($slug) {
    $room = Motelroom::where('slug', $slug)->firstOrFail();
    $room->increment('count_view');
    $categories = Categories::all();
    return view('home.detail', ['motelroom' => $room, 'categories' => $categories]);
});

// User report route
Route::get('/report/{id}', [MotelController::class, 'userReport'])->name('user.report');

// User delete motel room route
Route::get('/motelroom/del/{id}', [MotelController::class, 'user_del_motel']);

// User routes
Route::prefix('user')->group(function () {
    Route::get('register', [UserController::class, 'get_register']);
    Route::post('register', [UserController::class, 'post_register'])->name('user.register');

    Route::get('login', [UserController::class, 'get_login']);
    Route::post('login', [UserController::class, 'post_login'])->name('user.login');
    Route::get('logout', [UserController::class, 'logout']);

    Route::middleware('dangtinmiddleware')->group(function () {
        Route::get('dangtin', [UserController::class, 'get_dangtin']);
        Route::post('dangtin', [UserController::class, 'post_dangtin'])->name('user.dangtin');

        Route::get('profile', [UserController::class, 'getprofile']);
        Route::get('profile/edit', [UserController::class, 'getEditprofile']);
        Route::post('profile/edit', [UserController::class, 'postEditprofile'])->name('user.edit');
    });
});
// Route for displaying rented rooms
// Route cho phòng còn trống
Route::get('admin/motelrooms/available', [MotelController::class, 'getAvailableRooms'])->name('motelrooms.available');

// Route cho phòng đã thuê
Route::get('admin/motelrooms/rented', [MotelController::class, 'getRentedRooms'])->name('motelrooms.rented');


// Search motel route
// Route::post('searchmotel', [MotelController::class, 'SearchMotelAjax']);
// Route để xóa báo cáo
Route::get('/search-motel', [MotelController::class, 'SearchMotel'])->name('search.motel');

Route::delete('admin/report/{id}', [AdminController::class, 'deleteReport'])->name('admin.report.delete');





Route::middleware(['auth'])->group(function () {
    Route::get('/payment/{id}', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');
});

Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
