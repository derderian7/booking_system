<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Service;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses= Business::all();
        return view('admin/business',compact('businesses'));
    }

    public function edit($id)
    {
        $business = Business::findOrFail($id);
        return view('admin/edit_business',compact('business'));   
    }

    public function create()
    {
        $users= User::where('role','salon')->get();
        return view('admin/create_business',compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|max:255',
            'location'=>'required',
            'opening_hours'=>'required',
            'user_id'=>'required',
            'hijabis'=>'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $new_business = new Business();
        $new_business->name = $request->name;
        $new_business->location = $request->location;
        $new_business->opening_hours = $request->opening_hours;
        $new_business->status = 'open';
        $new_business->user_id = $request->user_id;
        $new_business->hijabis = $request->hijabis;
        $path = 'assets/uploads/business/' . $new_business->image;
        if (File::exists($path)) {
           File::delete($path);
       }
       $file = $request->file('image');
       $ext = $file->getClientOriginalExtension();
       $filename = time() . '.' . $ext;
      try{
       $file->move('assets/uploads/business/', $filename);
      }catch(FileException $e){
       dd($e);
      }
        $new_business->image = $filename;
        $new_business->save();
        return redirect('business')->with('message','Business is deleted!');
    }

    public function update($id, Request $request)
    {
        $business = Business::findOrFail($id);
        $request->validate([
            'name'=>'required|max:255',
            'location'=>'required',
            'opening_hours'=>'required',
            'status'=>'required',
            'user_id'=>'required',
            'hijabis'=>'required'
        ]);
        if($request->hasFile('image')){
            $path = 'assets/uploads/business/' . $business->image;
            if (File::exists($path)) {
               File::delete($path);
           }
           $file = $request->file('image');
           $ext = $file->getClientOriginalExtension();
           $filename = time() . '.' . $ext;
          try{
           $file->move('assets/uploads/business', $filename);
          }catch(FileException $e){
           dd($e);
          }
            $business->image = $filename;
         }
         $business->name = $request->name;
         $business->location = $request->location;
         $business->opening_hours = $request->opening_hours;
         $business->status = 'open';
         $business->user_id = $request->user_id;
         $business->hijabis = $request->hijabis;
        $business->save();
        return redirect('admin/business')->with('message','Business is updated!');
    }

    public function destroy($id)
    {
        $business=Business::findorfail($id);
        $business->delete();
        return redirect('business')->with('message','Business is deleted!');
    }

    public function business_service_view()
    {
        $users = Business::all();
        return view('admin/business_services',compact('users'));
    }

    public function service_business(Request $request)
    {
        try{
        $service = new Service();
            $service->service = $request->service;
            $service->price = $request->price;
            $service->duration = $request->duration;
            $service->business_id = $request->business_id;
            $service->type = $request->type;
            $service->name = $request->name;
            $service->save();
            $services = Service::all();
        return $services;

    }catch(Exception $e){
        return response()->json(['result'=>null,'error'=>$e],500);
        }
    }

}
