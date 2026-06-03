<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TelemetryLog;
use App\Models\ActuatorState;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    // Combined endpoint: returns sensor data + actuator status in ONE call
    public function dashboard(Request $request)
    {
        // Latest sensor readings
        $latestLight = TelemetryLog::where('sensor_type', 'light')->latest()->first();
        $latestMotion = TelemetryLog::where('sensor_type', 'motion')->latest()->first();

        // Actuator states (flat format expected by mobile app)
        $actuators = ActuatorState::pluck('state', 'actuator_name');
        // Convert to boolean-compatible format
        $actuatorStatus = [
            'green_led'  => (int) ($actuators['green_led']  ?? 0),
            'yellow_led' => (int) ($actuators['yellow_led'] ?? 0),
            'buzzer'     => (int) ($actuators['buzzer']     ?? 0),
        ];

        // Connection status
        $lastTelemetry = TelemetryLog::latest()->first();
        $isConnected = false;
        $lastPing = null;
        if ($lastTelemetry) {
            $lastPing = $lastTelemetry->created_at;
            $secondsAgo = now()->diffInSeconds($lastPing);
            $isConnected = $secondsAgo < 10;
        }

        return response()->json([
            'sensors' => [
                'light' => [
                    'value' => $latestLight ? (int) $latestLight->value : null,
                    'updated_at' => $latestLight ? $latestLight->created_at : null,
                ],
                'motion' => [
                    'value' => $latestMotion ? (int) $latestMotion->value : null,
                    'updated_at' => $latestMotion ? $latestMotion->created_at : null,
                ],
            ],
            'actuators' => $actuatorStatus,
            'connection' => [
                'connected' => $isConnected,
                'last_ping_human' => $lastPing ? $lastPing->diffForHumans() : 'Never',
            ],
        ]);
    }
}