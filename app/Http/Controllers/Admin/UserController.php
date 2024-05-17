<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users= User::all();
        return view('admin/users',compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin/edit_user',compact('user'));   
    }

    public function create()
    {
        return view('admin/create_user');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'phone_number' => 'required|string|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return redirect()->back();
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'phone_number' => 'required|string|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->password = bcrypt($request->password);
        return redirect()->back();
    }

    public function destroy($id)
    {
        $business=User::findorfail($id);
        $business->delete();
        return redirect()->back();
    }

    public function salons()
    {
        $users= User::where('role','salon')->get();
        return view('admin/users',compact('users'));
    }

}
