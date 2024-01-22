<?php
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\Admin_loginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('admin')->group(function(){
    Route::get('user',[UserController::class,'index'])->name('user');
    Route::post('store',[UserController::class,'store'])->name('new_user');
    Route::get('user-create',[UserController::class,'create'])->name('user.create');
    Route::get('user-delete/{id}',[UserController::class,'destroy'])->name('user.delete');

    Route::get('businesses',[BusinessController::class,'index'])->name('businesses');
    Route::post('business-store',[BusinessController::class,'store'])->name('new_business');
    Route::get('business-create',[BusinessController::class,'create'])->name('business.create');
    Route::get('business-delete/{id}',[BusinessController::class,'destroy'])->name('business.delete');
});

Route::post('admin_login',[Admin_loginController::class,'login'])->name('admin_login');
Route::get('showloginform',[Admin_loginController::class,'showloginform'])->name('login_form');
Route::get('logout',[Admin_loginController::class,'logout'])->name('logout');