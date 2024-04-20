<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserChoice;

class UserChoiceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'choice' => 'required|in:pulmonary fibrosis,pulmonary embolism,pneumonia,interstitial lungs',
        ]);

        $choice = new UserChoice();
        $choice->choice = $request->choice;
        $choice->save();

        return response()->json(['message' => 'Choice stored successfully'], 201);
    }
}
