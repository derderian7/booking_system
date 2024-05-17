<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Business\BookingController as BusinessBookingController;
use App\Http\Controllers\Business\ServicesController;
use App\Http\Controllers\Business\TransactionsController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\NotificationSendController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});
Route::middleware(['auth:sanctum'])->group(function () {
Route::resource('service',ServicesController::class);
Route::get('business_services',[ServicesController::class,'business_services']);
Route::post('update_service/id',[ServicesController::class,'update_service']);
Route::resource('reviews',ReviewsController::class);
Route::resource('business',BusinessController::class);
Route::resource('booking',BookingController::class);
Route::get('user_bookings',[BookingController::class,'user_bookings']);
Route::get('business_bookings',[BusinessBookingController::class,'business_bookings']);
Route::get('getAvailableHours/{serviceId}/{selectedDate}',[BookingController::class,'getAvailableHours']);
Route::get('services_types',[BusinessController::class,'services_types']);
Route::get('business_details',[BusinessController::class,'business_details']);
Route::post('change_booking_status/{id}',[BusinessBookingController::class,'change_booking_status']);
Route::post('change_booking_price/{id}',[BusinessBookingController::class,'change_booking_price']);
Route::post('update_business',[ServicesController::class,'business_services']);
Route::post('update_fcm_token', [NotificationSendController::class, 'updateDeviceToken']);

Route::get('transactions',[TransactionsController::class,'index']);

Route::get('remaining',[TransactionsController::class,'remaining']);

Route::post('/send-web-notification', [NotificationSendController::class, 'sendNotification']);

});
