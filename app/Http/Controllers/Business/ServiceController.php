<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ServicesController extends Controller
{
    public function index()
    {
        $service= Service::with('business')->paginate(10);
        return response()->json(['result'=>$service,'error'=>null],200);
    }

    public function store(Request $request)
    {
        try{
        $business= Business::where('user_id',Auth::id())->first();
        $service = new Service();
            $service->service = $request->service;
            $service->price = $request->price;
            $service->duration = $request->duration;
            $service->business_id = $business->id;
            $service->type = $request->type;
            $service->name = $request->name;
            $service->save();

        return response()->json(['result'=>['message'=>'Service is added'],'error'=>null],200);
        
    }catch(Exception $e){
        return response()->json(['result'=>null,'error'=>$e],500);
        }
    }

    public function update_service(Request $request,$id)
    {
        try{
        $business= Business::where('user_id',Auth::id())->first();
        $service = Service::find($id);
            $service->service = $request->service;
            $service->price = $request->price;
            $service->duration = $request->duration;
            $service->business_id = $business->id;
            $service->type = $request->type;
            $service->name = $request->name;
            $service->status = $request->status;
            $service->save();

        return response()->json(['result'=>['message'=>'Service is Updated'],'error'=>null],200);
        
    }catch(Exception $e){
        return response()->json(['result'=>null,'error'=>$e],500);
        }
    }

    public function business_services()
    {
        $services = Service::where('business_id',Auth::id())->paginate(10);
        return response()->json(['result'=>$services,'error'=>null],200);
    }

    public function update_business(Request $request)
    {
        $business= Business::where('user_id',Auth::id())->first();
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
        return response()->json(['result'=>'Business is updated','error'=>null],200);
    }
}
