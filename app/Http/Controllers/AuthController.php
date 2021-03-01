<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'reset']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required|min:4',
        ]);
        $credentials = request(['email', 'password']);

        if (!User::where('email', request('email'))->exists())
        {
            return response()->json(['error' => 'no user with such email'], 404);
        }

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        User::where('email', request('email'))->update(['last_login_at' => \Carbon\Carbon::now()]);

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Register new user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        request()->validate([
            'username' => 'required|max:255',
            'email' => 'required|unique:users|regex:/^.+@.+$/i',
            'password' => 'required|min:4',
        ]);
        if (User::where('email', request('email'))->exists())
        {
            return response()->json(['error' => 'user with such email already exists'], 409);
        }

        User::create([
            'username' => request('username'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        return $this->login();
    }

    /**
     * Reset users password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset() {
        request()->validate([
            'email' => 'required',
        ]);
        if (!User::where('email', request('email'))->exists())
        {
            return response()->json(['error' => 'no user with such email'], 404);
        }

        // TODO: send notification to email

        return response()->json(['message' => 'Check your email.']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
