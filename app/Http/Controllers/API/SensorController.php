<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TelemetryLog;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    // ESP32 sends sensor data here
    public function ingest(Request $request)
    {
        $validated = $request->validate([
            'temperature' => 'nullable|numeric',
            'humidity' => 'nullable|numeric',
            'light' => 'nullable|integer',
            'motion' => 'nullable|integer',
        ]);
        
        if (isset($validated['light'])) {
            TelemetryLog::create([
                'sensor_type' => 'light',
                'value' => $validated['light'],
                'unit' => 'digital'
            ]);
        }
        
        if (isset($validated['motion'])) {
            TelemetryLog::create([
                'sensor_type' => 'motion',
                'value' => $validated['motion'],
                'unit' => 'digital'
            ]);
        }
        
        if (isset($validated['temperature'])) {
            TelemetryLog::create([
                'sensor_type' => 'temperature',
                'value' => $validated['temperature'],
                'unit' => 'C'
            ]);
        }
        
        if (isset($validated['humidity'])) {
            TelemetryLog::create([
                'sensor_type' => 'humidity',
                'value' => $validated['humidity'],
                'unit' => '%'
            ]);
        }
        
        return response()->json(['success' => true], 201);
    }
    
    // Mobile app gets latest sensor data
    public function latest()
    {
        try {
            $latestLight = TelemetryLog::where('sensor_type', 'light')
                ->latest()
                ->first();
                
            $latestMotion = TelemetryLog::where('sensor_type', 'motion')
                ->latest()
                ->first();
            
            return response()->json([
                'light' => [
                    'value' => $latestLight ? (int)$latestLight->value : null,
                ],
                'motion' => [
                    'value' => $latestMotion ? (int)$latestMotion->value : null,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Latest sensor error: ' . $e->getMessage());
            return response()->json([
                'light' => ['value' => null],
                'motion' => ['value' => null],
            ]);
        }
    }
}