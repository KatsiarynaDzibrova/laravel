<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'reset', 'reset_password', 'verify']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!User::where('email', request('email'))->exists()) {
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
        if (User::where('email', request('email'))->exists()) {
            return response()->json(['error' => 'user with such email already exists'], 409);
        }

        if (strlen(request('password')) < 8) {
            return response()->json(['error' => 'password must be at least 8 symbols long'], 401);
        }

        $user = User::create([
            'username' => request('username'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        event(new Registered($user));

        return $this->login();
    }

    /**
     * Reset users password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset()
    {
        if (!User::where('email', request('email'))->exists()) {
            return response()->json(['error' => 'no user with such email'], 404);
        }

        $status = Password::sendResetLink(
            request()->only('email')
        );

        $message = $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);

        return response()->json(['message' => $message]);
    }

    /**
     * Reset users password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset_password()
    {
        $request = request();

        $status = Password::reset(
            request()->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request)
            {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        $message = $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);

        return response()->json(['message' => $message]);
    }

    /**
     * Verify users e-mail.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify($id)
    {
        User::find($id)->update(['email_verified_at' => \Carbon\Carbon::now()]);

        return response()->json(['message' => 'Mail verified']);
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
