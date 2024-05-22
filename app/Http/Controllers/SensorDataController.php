<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Models\Sensor_Data;
use Illuminate\Http\Request;
use App\Http\Controllers;


class SensorDataController extends Controller
{
    public function makePrediction()
    {
        // Fetch the latest record from the sensor_data table
        //$data = Sensor_Data::latest()->first();
        $data = Sensor_Data::orderBy('id', 'desc')->first();
        if (!$data) {
            return response()->json(['error' => 'No data found'], 404);
        }

        // Log the data retrieved from the database
        Log::info('Latest sensor data: ', $data->toArray());

        // Prepare the data to send to the Flask API
        $parameters = [
            'oxy' => $data->oxygen_rate,
            'pulse' => $data->heart_rate,
            'clieus' => $data->clieus,
        ];

        // Make a POST request to the Flask API
        $response = Http::post('http://127.0.0.1:8080/predict', $parameters);

        if ($response->successful()) {
            $prediction = $response->json()['prediction'];

            // Save the prediction back to the database
            $data->prediction = $prediction;
            $data->save();

            return response()->json(['prediction' => $prediction], 200);
        } else {
            return response()->json(['error' => 'Prediction API failed'], 500);
        }
    }
}
