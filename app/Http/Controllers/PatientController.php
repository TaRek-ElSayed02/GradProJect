<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
#added
use Tymon\JWTAuth\Facades\JWTAuth;
#---------
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class PatientController extends Controller
{
    public function pregister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Doctor_id' => 'required|exists:doctors,id',
            'Email' => 'required|string|email|unique:patients,Email',
            'Password' => 'required|string|min:7',
            'Password_Confirmation' => 'required|string|min:7|same:Password',
            // Add other validation rules if needed
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $patient= Patient::create(array_merge(
            $validator->validated(), 
            ['Password' => bcrypt($request->Password)],
            #added
            ['Password_Confirmation' => bcrypt($request->Password_Confirmation)],
 
        ));
        return response()->json([
            'message' => 'Patient successfully registered',
            'Patient' => $patient
        ], 201);
    }

    public function plogin(Request $request)
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
    
        $authenticatedPatient = Patient::where('Email', $email)->first();
    
        if ($authenticatedPatient && Hash::check($password, $authenticatedPatient->Password)) {
            $token = $authenticatedPatient->createToken('PatientAuthToken')->plainTextToken;
    
            return $this->createToken($token, $email, $password);  
        } else {
            return response()->json(['error' => 'Unauthorized' ], 401);
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
  

    public function form(Request $request, $id)
    {
        
        $patient = Patient::find($id);
    
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        // Log the request data
        \Log::info('Request data:', [
            'Name' => $request->Name,
            'Age' => $request->Age,
            'Height' => $request->Height,
            'Weight' => $request->Weight,
            'Temperature' => $request->Temperature,
            'Phonenumber' => $request->Phonenumber
        ]);
    
        // Update patient's profile
        $patient->update([
            'Name' => $request->Name,
            'Age' => $request->Age,
            'Height' => $request->Height,
            'Weight' => $request->Weight,
            'Temperature' => $request->Temperature,
            'Phonenumber' => $request->Phonenumber
        ]);
    
        return response()->json(['message' => 'Profile updated successfully'], 200);
    }


   #logout---------------------------------------------->>>>>
  
   
    
}
