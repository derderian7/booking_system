<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
{
    public function index()
    {
        $reviews= Reviews::with('user')->paginate(10);
        return response()->json(['result'=>$reviews,'error'=>null],200);
    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
            'user_id'=>'required',
            'review'=>'required',
            'stars'=>'required',
            'business_id'=>'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $review = New Reviews();
        $review->user_id= $request->user_id;
        $review->review= $request->review;
        $review->stars= $request->stars;
        $review->business_id= $request->business_id;
        $review->save();
        return response()->json(['result'=>['message'=>'Review is added'],'error'=>null],200);
        }catch(Exception $e){
            return response()->json(['result'=>null,'error'=>$e],500);
        }
    }

    public function destroy($id)
    {
        $review=Reviews::find($id);
        if($review){
        $review->delete();
        return response()->json(['result'=>['message'=>'Review is deleted']],200);
        }else         
        return response()->json(['result'=>null,'error'=>'Review does not exist'],200);
    }
}