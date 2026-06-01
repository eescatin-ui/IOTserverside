<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        if (! $user->api_token) {
            $user->api_token = Str::random(60);
            $user->save();
        }

        return response()->json([
            'token' => $user->api_token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'sometimes|string|in:admin,operator',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'] ?? 'operator',
            'api_token' => Str::random(60),
        ]);

        return response()->json([
            'message' => 'Account created successfully',
            'token' => $user->api_token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 201);
    }

    public function profile(Request $request)
    {
        $user = $this->authenticate($request);

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    public function listUsers(Request $request)
    {
        $user = $this->authenticate($request);

        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(
            User::select('id', 'name', 'email', 'role', 'created_at')->orderBy('id')->get()
        );
    }

    public function activities(Request $request)
    {
        $user = $this->authenticate($request);

        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(
            ActivityLog::with('user')
                ->orderByDesc('created_at')
                ->limit(200)
                ->get()
        );
    }

    private function authenticate(Request $request): ?User
    {
        $token = $request->bearerToken() ?: $request->header('X-API-TOKEN');

        if (! $token) {
            return null;
        }

        return User::where('api_token', $token)->first();
    }
}