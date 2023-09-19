<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResisterValidationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Spatie\FlareClient\Api;

class AuthController extends Controller
{
    public function register(ResisterValidationRequest $request){

        $data = $request->validated();
        $data['password']=Hash::make($data['password']);
        $user =User::create($data);


        $token= $user->createToken('apiToken')->plainTextToken;
//        $data['name'] = $user->name;
//        $data['email'] = $user->email;

        return ApiResponse::sendResponse(201 , 'User Created Successfully' , $data);


    }// end of register function

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
           'email' => ['required' , 'email' , 'max:255'],
           'password' => ['required']
        ]);
        if ($validator->fails()){
            return ApiResponse::sendResponse(422 , 'Login Validation Error' , $validator->messages()->all());
        }
        if (Auth::attempt(['email' => $request->email , 'password' => $request->password])){
            $user = Auth::user();
            $data['token'] = $user->createToken('apiCourse')->plainTextToken;
            $data['name'] = $user->name;
            $data['mail'] = $user->email;
            return ApiResponse::sendResponse(200 , 'Login Successfuly' , $data);
        }else{
            return ApiResponse::sendResponse(401 , 'These credentials does not exist' , null);
        }

    }// end of login function

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse(200 , 'Logout Successfuly' , null);

    }

}// end of controller
