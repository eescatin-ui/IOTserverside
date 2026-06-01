<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ActuatorState;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActuatorController extends Controller
{
    // ESP32 polls this to get actuator states
    public function getStatus()
    {
        $states = ActuatorState::pluck('state', 'actuator_name');
        return response()->json($states);
    }
    
    private function authenticate(Request $request): ?User
    {
        $token = $request->bearerToken() ?: $request->header('X-API-TOKEN');
        if (! $token) {
            return null;
        }
        return User::where('api_token', $token)->first();
    }

    private function logActivity(?User $user, string $actuatorName, string $action, string $state, string $source = 'mobile')
    {
        ActivityLog::create([
            'user_id' => $user?->id,
            'actuator_name' => $actuatorName,
            'action' => $action,
            'state' => $state,
            'source' => $source,
        ]);
    }

    // Mobile app sends control commands here
    public function control(Request $request)
    {
        $user = $this->authenticate($request);
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'actuator' => 'required|string',
            'state' => 'required|boolean',
            'source' => 'sometimes|string'
        ]);
        
        $actuator = ActuatorState::where('actuator_name', $validated['actuator'])->first();
        
        if ($actuator) {
            $actuator->update([
                'state' => $validated['state'],
                'controlled_by' => $user->email,
            ]);

            $this->logActivity(
                $user,
                $validated['actuator'],
                $validated['state'] ? 'enabled' : 'disabled',
                $validated['state'] ? 'on' : 'off',
                $validated['source'] ?? 'mobile'
            );

            return response()->json(['success' => true]);
        }
        
        return response()->json(['error' => 'Actuator not found'], 404);
    }
    
    // ==========================================
    // BUZZER DURATION CONTROL (1=2s, 2=4s, 3=6s)
    // ==========================================
    
    // ✅ NEW: Public endpoint for ESP32 - no authentication required
    public function getDurationForESP32()
    {
        $duration = ActuatorState::where('actuator_name', 'buzzer_duration')->first();
        return response()->json(['duration' => $duration ? (int)$duration->state : 2]);
    }
    
    // Get buzzer duration (for authenticated users)
    public function getDuration()
    {
        $duration = ActuatorState::where('actuator_name', 'buzzer_duration')->first();
        return response()->json(['duration' => $duration ? (int)$duration->state : 2]);
    }
    
    // Set buzzer duration
    public function setDuration(Request $request)
    {
        $user = $this->authenticate($request);
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'duration' => 'required|integer|min:1|max:3'
        ]);
        
        $duration = ActuatorState::updateOrCreate(
            ['actuator_name' => 'buzzer_duration'],
            [
                'state' => $validated['duration'],
                'controlled_by' => $user->email
            ]
        );

        $this->logActivity(
            $user,
            'buzzer_duration',
            'duration_updated',
            (string) $validated['duration'],
            'mobile'
        );
        
        return response()->json([
            'success' => true,
            'duration' => $duration->state
        ]);
    }
}