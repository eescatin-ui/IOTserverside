<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TelemetryLog;
use App\Models\ActuatorState;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function authenticate(Request $request): ?User
    {
        $token = $request->bearerToken() ?: $request->header('X-API-TOKEN');
        if (! $token) {
            return null;
        }
        return User::where('api_token', $token)->first();
    }

    // Get connection status - ESP32 last ping time
    public function connectionStatus()
    {
        $lastTelemetry = TelemetryLog::latest()->first();
        $isConnected = false;
        $lastPing = null;

        if ($lastTelemetry) {
            $lastPing = $lastTelemetry->created_at;
            $secondsAgo = now()->diffInSeconds($lastPing);
            $isConnected = $secondsAgo < 10; // Connected if data within last 10 seconds
        }

        return response()->json([
            'connected' => $isConnected,
            'last_ping' => $lastPing,
            'last_ping_human' => $lastPing ? $lastPing->diffForHumans() : 'Never',
        ]);
    }

    // Get full dashboard data
    public function dashboard(Request $request)
    {
        $user = $this->authenticate($request);
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Latest sensor readings
        $latestLight = TelemetryLog::where('sensor_type', 'light')->latest()->first();
        $latestMotion = TelemetryLog::where('sensor_type', 'motion')->latest()->first();

        // Actuator states
        $actuators = ActuatorState::all()->map(function ($actuator) {
            $stateValue = $actuator->actuator_name === 'buzzer_duration' 
                ? (int) $actuator->state 
                : (bool) $actuator->state;
            return [
                'id' => $actuator->id,
                'actuator_name' => $actuator->actuator_name,
                'state' => $stateValue,
                'controlled_by' => $actuator->controlled_by,
                'updated_at' => $actuator->updated_at,
            ];
        });

        // Connection status
        $lastTelemetry = TelemetryLog::latest()->first();
        $isConnected = false;
        $lastPing = null;
        if ($lastTelemetry) {
            $lastPing = $lastTelemetry->created_at;
            $secondsAgo = now()->diffInSeconds($lastPing);
            $isConnected = $secondsAgo < 10;
        }

        // Recent telemetry (last 50 entries)
        $recentTelemetry = TelemetryLog::latest()->limit(50)->get();

        // Recent activity logs (last 50)
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(50)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'user' => $log->user ? $log->user->email : 'system',
                    'actuator_name' => $log->actuator_name,
                    'action' => $log->action,
                    'state' => $log->state,
                    'source' => $log->source,
                    'created_at' => $log->created_at,
                ];
            });

        return response()->json([
            'connection' => [
                'connected' => $isConnected,
                'last_ping' => $lastPing,
                'last_ping_human' => $lastPing ? $lastPing->diffForHumans() : 'Never',
            ],
            'sensors' => [
                'light' => [
                    'value' => $latestLight ? (int) $latestLight->value : null,
                    'unit' => $latestLight ? $latestLight->unit : null,
                    'updated_at' => $latestLight ? $latestLight->created_at : null,
                ],
                'motion' => [
                    'value' => $latestMotion ? (int) $latestMotion->value : null,
                    'unit' => $latestMotion ? $latestMotion->unit : null,
                    'updated_at' => $latestMotion ? $latestMotion->created_at : null,
                ],
            ],
            'actuators' => $actuators,
            'telemetry' => $recentTelemetry,
            'activities' => $recentActivities,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    // Lightweight polling endpoint for real-time dashboard updates
    public function dashboardPoll(Request $request)
    {
        $user = $this->authenticate($request);
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Latest sensor readings (light queries only)
        $latestLight = TelemetryLog::where('sensor_type', 'light')->latest()->first();
        $latestMotion = TelemetryLog::where('sensor_type', 'motion')->latest()->first();

        // Connection status
        $lastTelemetry = TelemetryLog::latest()->first();
        $isConnected = false;
        $lastPing = null;
        if ($lastTelemetry) {
            $lastPing = $lastTelemetry->created_at;
            $secondsAgo = now()->diffInSeconds($lastPing);
            $isConnected = $secondsAgo < 10;
        }

        // Actuator states
        $actuators = ActuatorState::all()->map(function ($actuator) {
            $stateValue = $actuator->actuator_name === 'buzzer_duration' 
                ? (int) $actuator->state 
                : (bool) $actuator->state;
            return [
                'id' => $actuator->id,
                'actuator_name' => $actuator->actuator_name,
                'state' => $stateValue,
                'controlled_by' => $actuator->controlled_by,
                'updated_at' => $actuator->updated_at,
            ];
        });

        return response()->json([
            'connection' => [
                'connected' => $isConnected,
                'last_ping_human' => $lastPing ? $lastPing->diffForHumans() : 'Never',
            ],
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
            'actuators' => $actuators,
        ]);
    }

    // Web dashboard control actuator
    public function webControl(Request $request)
    {
        $user = $this->authenticate($request);
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'actuator' => 'required|string|in:green_led,yellow_led,buzzer,buzzer_duration',
            'state' => 'required',
        ]);

        $actuator = ActuatorState::where('actuator_name', $validated['actuator'])->first();

        if ($actuator) {
            $stateValue = is_bool($validated['state']) 
                ? ($validated['state'] ? 1 : 0) 
                : $validated['state'];

            $actuator->update([
                'state' => $stateValue,
                'controlled_by' => $user->email,
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'actuator_name' => $validated['actuator'],
                'action' => $stateValue ? 'enabled' : 'disabled',
                'state' => (string) $stateValue,
                'source' => 'web',
            ]);

            return response()->json(['success' => true, 'actuator' => $actuator->fresh()]);
        }

        return response()->json(['error' => 'Actuator not found'], 404);
    }
}