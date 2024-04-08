<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
#added


#------
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
//use Validator;
use Illuminate\Database\Eloquent\Factories\Factory;


class DoctorController extends Controller{

    public function register(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'Email'=>'required|string|email|unique:doctors,Email',
            'Password'=>'required|string|min:7',
            'Password_Confirmation'=>'required|string|min:7'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);

        }
        $doctor= Doctor::create(array_merge(
            $validator->validated(), 
            ['Password' => bcrypt($request->Password)],
            #added
            ['Password_Confirmation' => bcrypt($request->Password_Confirmation)],
 
        ));
        return response()->json([
            'message'=>"user successfully registed",
            'Doctor'=>$doctor ],201);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Email' => 'required|email',
            'Password' => 'required|string|min:7',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $email = $request->input('Email');
        $password = $request->input('Password');
    
        $authenticatedDoctor = Doctor::where('Email', $email)->first();
    
        if ($authenticatedDoctor && Hash::check($password, $authenticatedDoctor->Password)) {
            $token = $authenticatedDoctor->createToken('DoctorAuthToken')->plainTextToken;
    
            return $this->createToken($token, $email, $password);  
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    
    public function createToken($token, $email, $password)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'email' => $email,
            'password' => $password,
        ]);
    }
    

    #logout for doctor
   public function logout(Request $request){
    
    $authenticatedDoctor = Auth::guard('doctor')->user();

    // Delete the current access token
    $authenticatedDoctor->currentAccessToken()->delete();

    // Return a response indicating successful logout
    return ApiResponse::sendResponse(204, 'Logged out successfully');

   }
}