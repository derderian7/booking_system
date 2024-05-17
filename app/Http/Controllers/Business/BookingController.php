<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function business_bookings()
    {
        $business =  Business::where('user_id', Auth::id())->first();
        $bookings = Booking::where('business_id',$business->id)
        ->with('user','service')
        ->paginate(10);
        foreach($bookings as $booking){
        $booking->percentage = $booking->price * $booking->business->percentage/100;
        };
        return response()->json(['result'=>$bookings,'error'=>null],200);
    }

    public function change_booking_status($id, Request $request)
    {
        $booking=Booking::find($id);
        if($booking){
            $booking->status=$request->status;
            $booking->update();
             return response()->json(['result'=>['message'=>"Booking status changed successfully"],'error'=>null],200);
             }else 
             return response()->json(['result'=>null,'error'=>"error changing the status"],500);
    }

    public function change_booking_price($id,Request $request)
    {
        $booking=Booking::find($id);
        if($booking){
            $booking->price=$request->price;
            $booking->update();
             return response()->json(['result'=>['message'=>"Booking price changed successfully"],'error'=>null],200);
             }else 
             return response()->json(['result'=>null,'error'=>"error changing the status"],500);
    }
    
}