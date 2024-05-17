<?php

namespace App\Http\Controllers\Business;


use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    public function index()
    {
        $business =  Business::where('user_id', Auth::id())->first();

        $transacations = Transaction::where('business_id', $business->id)
        ->paginate(10);

        return response()->json(['result'=>$transacations,'error'=>null],200);
    }

    public function remaining()
    {
        $business =  Business::where('user_id', Auth::id())->first();
        $remaining = Transaction::where('business_id', $business)
        ->latest()
        ->first(['remaining']);

        return response()->json(['result'=>$remaining,'error'=>null],200);
    }
}