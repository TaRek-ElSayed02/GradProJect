<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
#added
use Tymon\JWTAuth\Facades\JWTAuth;
#added token 
use Laravel\Sanctum\PersonalAccessToken;
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
        $passwordConfirmation = $request->Password_Confirmation;

        $patient= Patient::create(array_merge(
            $validator->validated(), 
            ['Password' => bcrypt($request->Password)],
            #added
            ['Password_Confirmation' => $request->Password_Confirmation],
 
        ));
        return response()->json([
            'message' => 'Patient successfully registered',
            'Patient' => $patient,
            'Password_Confirmation' => $passwordConfirmation,
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
  
#commint
    public function form(Request $request, $id)
    {
       /* 
        $patient = Patient::find($id);
    
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $PhoneNumber = $request->PhoneNumber;
        // Log the request data
        \Log::info('Request data:', [
            'Name' => $request->Name,
            'Age' => $request->Age,
            'Height' => $request->Height,
            'Weight' => $request->Weight,
            'Temperature' => $request->Temperature,
            'PhoneNumber' => $request->PhoneNumber,
            
        ]);
        
    
        // Update patient's profile
        $patient->update([
            'Name' => $request->Name,
            'Age' => $request->Age,
            'Height' => $request->Height,
            'Weight' => $request->Weight,
            'Temperature' => $request->Temperature,
            'PhoneNumber' => $request->PhoneNumber, 
        ]);
    
        return response()->json(['message' => 'Profile updated successfully',
        'PhoneNumber' => $PhoneNumber,
    ], 200);

*/
     // Validate the incoming request data
     $validatedData = $request->validate([
        'Name' => 'required|string',
        'Age' => 'nullable|integer|min:0',
        'Height' => 'nullable|integer|min:0',
        'Weight' => 'nullable|integer|min:0',
        'Temperature' => 'nullable|numeric|min:0',
        'PhoneNumber' => 'nullable|string|max:20', // Adjust max length as needed
     ]);

    // Retrieve the patient by ID
    $patient = Patient::find($id);

    // Check if the patient exists
    if (!$patient) {
        return response()->json(['error' => 'Patient not found'], 404);
    }

    // Log the request data
    \Log::info('Request data:', $validatedData);

    // Update patient's profile
    $patient->update($validatedData);

    // Return success response
    return response()->json(['message' => 'Profile updated successfully'], 200);


    }


   #retrieve patient info---------------------------------------------->>>>>
   public function patientinfo()
   {
       $patients = Patient::all(); // Retrieve all patients
       return response()->json($patients);
   }
   

   #logout
   public function plogout(Request $request)
    {
        $acessToken = $request->bearerToken();
        $token = PersonalAccessToken::findToken($acessToken);
        $token->delete();
        return response(
            [
                'message' =>'Patient logout successfuly',
                'status' => 'success'
            ], status:200);
    }

    #search
    public function search($Name)
    {
        return Patient::where("Name",$Name)->get();

    }


    #list
    public function list(Request $request)
    {
        $patients = Patient::take(10)->get(); // Retrieve 10 patients
        return response()->json(['patients' => $patients], 200);
    }      
    
    #this is patient when choose one disease from radio button screen

    #delete patient according to his id 
    public function destroy($id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $patient->delete();
            
            return response()->json(['message' => 'Patient deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete patient'], 500);
        }
    }
    
}


