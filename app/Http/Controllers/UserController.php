<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all() , [
            'name'=>'required|min:3|max:35',
            'phone_number'=>'required|digits:11|unique:users',
            'password'=>'required|confirmed',
            // 'email'=>'required|email|unique:users',
        ]);
    
        if($validation->fails())
        {
            return response($validation->errors());
        }

        $user = User::create([
            'name'=>$request->name,
            'phone_number'=>$request->phone_number,
            'password'=>bcrypt($request->password),
            // 'email'=>$request->email,
        ]);
        
        return response(['success'=>true , 'data'=>$user]);
        // $user = User::create(array_merge($validation->validated() , ['password' => bcrypt($request->password)]));
    }
    
    public function login(Request $request)
    {
        // if (!$user || !Hash::check($request->password, $user->password))
        if (!Auth::attempt(['phone_number'=>$request->phone_number , 'password'=>$request->password]))
        {
            return response(['message'=>'Unauthorized','code'=>401]);
        }

        $user= User::where('phone_number', $request->phone_number)->first();
        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = ['user'=>$user , 'token'=>$token , 'code'=>200];
        return response(['success'=>true , 'data'=>$response]);
    }
}
