<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'user_type' => 'nullable|in:user,organizer',
            'organization_name' => 'required_if:user_type,organizer|string|max:255',
            'organization_description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'user_type' => $validated['user_type'] ?? 'user',
                'status' => 'active',
            ]);

            // Create Organizer record if user_type is organizer
            if ($user->user_type === 'organizer') {
                Organizer::create([
                    'user_id' => $user->id,
                    'organization_name' => $validated['organization_name'],
                    'description' => $validated['organization_description'] ?? null,
                    'approval_status' => 'pending',
                ]);

                $user->load('organizer');
            }

            DB::commit();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => $user->user_type === 'organizer'
                    ? 'Organizer registration successful. Awaiting admin approval.'
                    : 'User registered successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not active',
            ], 403);
        }

        $user->update(['last_login' => now()]);

        // Load organizer relationship
        if ($user->user_type === 'organizer') {
            $user->load('organizer');
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        // Load organizer relationship
        if ($user->user_type === 'organizer') {
            $user->load('organizer');
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}
