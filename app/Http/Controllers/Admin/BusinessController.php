<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::all();
        return view('businesses',compact('businesses'));
    }

    public function create()
    {
        $users = User::all();
        return view('create_business',compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'user_id'=>'required',
            'opening_hours'=>'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson());
        }
        Business::create(array_merge($validator->validated()));
        return redirect()->back();
    }

    public function edit($id)
    {
        $business = Business::find($id);
        return view('edit_business',compact('business'));
    }

    public function update($id, Request $request)
    {
        $business = Business::findOrFail($id);
        
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'user_id'=>'required',
            'status'=>'required',
            'opening_hours'=>'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson());
        }
        
        $business->update(array_merge($validator->validated()));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();
        return redirect()->back();
    }
}