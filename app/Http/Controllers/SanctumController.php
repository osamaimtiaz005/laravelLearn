<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * =============================================================================
 * SANCTUM AUTH APIs — User model only (token auth for Postman / mobile)
 * =============================================================================
 *
 * WHAT is Sanctum?
 *   Laravel package that issues API tokens (and can also do SPA cookie auth).
 *   THIS lesson uses TOKEN auth: client stores a string and sends it back.
 *
 * WHAT it is NOT:
 *   - Not session login like /sessions (cookies + CSRF)
 *   - Not JWT you decode yourself (Sanctum stores hashed tokens in DB)
 *   - Not SPA cookie flow (that would use sanctum/csrf-cookie + statefulApi)
 *
 * FLOW (token API):
 *   1) POST /api/auth/register  → create User + return Bearer token
 *   2) POST /api/auth/login     → check email/password + return Bearer token
 *   3) Client saves token
 *   4) Later requests send:  Authorization: Bearer {token}
 *   5) middleware auth:sanctum looks up the token → $request->user() is that User
 *   6) POST /api/auth/logout    → delete THIS token (this device)
 *
 * SETUP already done in this project:
 *   composer require laravel/sanctum
 *   config/sanctum.php
 *   migration: personal_access_tokens
 *   User uses HasApiTokens
 *
 * Hub page (HTML): GET /sanctum
 * =============================================================================
 */
class SanctumController extends Controller
{
    /**
     * Learning hub — WEB route, not an API.
     * GET /sanctum
     */
    public function learning()
    {
        return view('sanctum.index');
    }

    /**
     * ---------------------------------------------------------------------
     * SIGNUP / REGISTER — public
     * ---------------------------------------------------------------------
     * POST /api/auth/register
     *
     * Body JSON:
     *   { "name": "Ali", "email": "ali@example.com", "password": "secret123" }
     *
     * Creates a users row, then createToken() inserts personal_access_tokens.
     *
     * plainTextToken is shown ONCE. Sanctum stores only a HASH of the token.
     * If the client loses it, they must login again (cannot look it up).
     *
     * Password: User casts password => hashed, so do NOT Hash::make() here
     * or it would be hashed twice and login would fail.
     *
     * "confirmed" rule (NOT used here):
     *   'password' => ['required', 'confirmed']
     *   requires a SECOND field named password_confirmation with the SAME value.
     *   That is for HTML forms with two password boxes.
     *   APIs usually send password once — clients do not send password_confirmation.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // Use $request->input('device_name', 'api') to get the device name from the incoming request.
        // Why? 'device_name' lets clients label their token per device (e.g., 'postman', 'iPhone', 'laptop'),
        // so the same user can have multiple tokens for different devices/applications.
        // If the client does not provide device_name, we default to 'api'.
        $tokenName = $request->input('device_name', 'api');
        // Create a new personal access token for the user using the chosen token name.
        // Token names help identify where tokens were issued for management and revocation.
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'message' => 'Registered. Save the token — it is shown only once.',
            'user' => $user->only(['id', 'name', 'email']),
            'token' => $token,
            'token_type' => 'Bearer',
            'how_to_use' => 'Authorization: Bearer ' . $token,
        ], 201);
    }

    /**
     * ---------------------------------------------------------------------
     * LOGIN — public
     * ---------------------------------------------------------------------
     * POST /api/auth/login
     *
     * Body JSON:
     *   { "email": "ali@example.com", "password": "secret123", "device_name": "postman" }
     *
     * Wrong email/password → 422 (ValidationException) so Postman sees JSON errors
     * the same way as other validation failures — not a vague 401 HTML page.
     *
     * Each login creates a NEW token (phone, Postman, laptop can all stay logged in).
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['sometimes', 'string', 'max:100'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();
        // $validated['password']: This is the plaintext password submitted by the user in the login request, after validation.
        // $user->password: This is the hashed password stored in the database for the user whose email matches.
        // Hash::check($validated['password'], $user->password):
        //    - The first parameter ($validated['password']) is the raw password from the user input.
        //    - The second parameter ($user->password) is the hashed password from the database.
        //    - Hash::check() will hash the raw input and compare it securely to the stored hash.
        //    - Returns true if they match (correct password), false otherwise.
        // Hash::check() compares the plaintext password (from request) to the stored hash.
        // Returns true if password is correct, false otherwise.
        // This helps authenticate users securely without ever storing or comparing plaintext passwords directly.

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $tokenName = $validated['device_name'] ?? 'api';
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'message' => 'Logged in. Send this token on later requests.',
            'user' => $user->only(['id', 'name', 'email']),
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * ME / PROFILE — protected (auth:sanctum)
     * ---------------------------------------------------------------------
     * GET /api/auth/me
     *
     * Header: Authorization: Bearer {token}
     *
     * $request->user() is the User that owns this token.
     * Without a valid token → 401 Unauthenticated.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $current = $user->currentAccessToken();

        return response()->json([
            'message' => 'You are authenticated via Sanctum.',
            'user' => $user->only(['id', 'name', 'email', 'created_at']),
            'current_token' => [
                'id' => $current->id ?? null,
                'name' => $current->name ?? null,
                'abilities' => $current->abilities ?? [],
                'last_used_at' => $current->last_used_at ?? null,
            ],
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * LOGOUT this device — protected
     * ---------------------------------------------------------------------
     * POST /api/auth/logout
     *
     * Deletes ONLY the token used on this request.
     * Other devices (other tokens) stay logged in.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out on this device. This token no longer works.',
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * LOGOUT ALL devices — protected
     * ---------------------------------------------------------------------
     * POST /api/auth/logout-all
     *
     * Deletes every personal access token for this user.
     * Phone + Postman + laptop all must login again.
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices. Every token for this user was deleted.',
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * LIST tokens (names only — never the secret) — protected
     * ---------------------------------------------------------------------
     * GET /api/auth/tokens
     *
     * Useful to see device_name values from createToken('postman').
     * The raw token string is NOT stored — only a hash — so it cannot be listed.
     */
    public function tokens(Request $request): JsonResponse
    {
        $tokens = $request->user()->tokens()->get(['id', 'name', 'abilities', 'last_used_at', 'created_at']);

        return response()->json([
            'count' => $tokens->count(),
            'data' => $tokens,
            'tip' => 'You cannot recover a lost token. Login again to get a new one.',
        ]);
    }
}
