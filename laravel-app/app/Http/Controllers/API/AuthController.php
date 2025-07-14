<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\RegisterAgentRequest;
use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Agent;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function registerUser(RegisterUserRequest $request)
    {
        $agent = null;
        
        // Check if referral code is provided
        if ($request->referral_code) {
            $agent = Agent::where('referral_code', $request->referral_code)->first();
            if (!$agent) {
                return response()->json([
                    'message' => 'Invalid referral code',
                    'errors' => ['referral_code' => ['Invalid referral code']]
                ], 422);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'agent_id' => $agent?->id,
            'language' => $request->language ?? 'en',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    /**
     * Register a new agent.
     */
    public function registerAgent(RegisterAgentRequest $request)
    {
        $agent = Agent::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = $agent->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Agent registered successfully',
            'agent' => $agent,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    /**
     * Register a new company.
     */
    public function registerCompany(RegisterCompanyRequest $request)
    {
        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'description' => $request->description,
            'password' => Hash::make($request->password),
        ]);

        $token = $company->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Company registered successfully. Awaiting admin approval.',
            'company' => $company,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    /**
     * Login user/agent/company.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['password']);
        $loginField = $request->login; // Can be email or phone
        
        // Determine login type and find user
        $user = null;
        $userType = null;

        // Try to find user by email or phone
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            // Email login - try user first, then company
            $user = User::where('email', $loginField)->first();
            $userType = 'user';
            
            if (!$user) {
                $user = Company::where('email', $loginField)->first();
                $userType = 'company';
            }
        } else {
            // Phone login - try user first, then agent
            $user = User::where('phone', $loginField)->first();
            $userType = 'user';
            
            if (!$user) {
                $user = Agent::where('phone', $loginField)->first();
                $userType = 'agent';
            }
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if company is approved
        if ($userType === 'company' && !$user->is_approved) {
            return response()->json([
                'message' => 'Company account is pending approval'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'user' => $user,
            'user_type' => $userType,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get authenticated user info.
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $userType = 'user';

        if ($user instanceof Agent) {
            $userType = 'agent';
        } elseif ($user instanceof Company) {
            $userType = 'company';
        }

        return response()->json([
            'user' => $user,
            'user_type' => $userType
        ]);
    }
}
