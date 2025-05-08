<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    /**
     * Register new user and log them in (cookie-based).
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['user' => $user]);
    }

    /**
     * Log in existing user (cookie-based).
     * Accepts optional boolean `remember`.
     */
    public function login(Request $request)
    {
        $creds = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ]);

        $remember = $creds['remember'] ?? false;
        unset($creds['remember']);

        if (! Auth::attempt($creds, $remember)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $request->session()->regenerate();

        return response()->json(['user' => Auth::user()]);
    }

    /**
     * Log out user (invalidate session + regenerate token).
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cookie::queue(Cookie::forget('remember_web'));
        Cookie::queue(Cookie::forget(config('session.cookie')));

        return response()->json(['message' => 'Logged out']);
    }

    /**
     * Return currently authenticated user.
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Send a password reset link to the given email.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['status' => __($status)]);
        }

        return response()->json(['email' => __($status)], 400);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|string|confirmed|min:8',
        ]);

        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['status' => __($status)]);
        }

        return response()->json(['email' => __($status)], 400);
    }
}
