<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;


class AuthController extends Controller
{
    /**
     * Register new user
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'User registered successfully. Please check your email for verification.',
            'user' => $user
        ], 201);
    }

    /**
     * Login user and create token
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ])->onlyInput('email');
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the user's session and regenerate the CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page or home
        return redirect()
            ->route('login')
            ->with('status', 'You have been logged out successfully.');
    }

    /**
     * Get authenticated user data
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    /**
     * Send password reset link
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : response()->json(['error' => __($status)], 400);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)])
            : response()->json(['error' => __($status)], 400);
    }

    /**
     * Verify email (should be accessed via email link)
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json(['message' => 'Email successfully verified']);
    }

    /**
     * Resend email verification link
     */
    public function resendVerificationEmail(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent']);
    }

    public function requestPasswordReset(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999); // 6-digit OTP

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp,
                'created_at' => Carbon::now(),
            ]
        );

        // Send email with OTP
        Mail::raw("Your password reset OTP is: {$otp}", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your Password Reset OTP');
        });

        return response()->json(['message' => 'OTP sent to your email.']);
    }


    public function verifyResetOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || $record->token !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP.'], 400);
        }

        // Optional: check OTP expiry (e.g., 15 min)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return response()->json(['error' => 'OTP expired.'], 400);
        }

        // Store a flag in session/cache or just move on
        return response()->json(['message' => 'OTP verified.']);
    }


    public function resetPasswordWithOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || $record->token !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP.'], 400);
        }

        // Optional expiry check
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return response()->json(['error' => 'OTP expired.'], 400);
        }

        // Reset password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Remove the OTP entry
        DB::table('password_resets')->where('email', $request->email)->delete();

        event(new PasswordReset($user));

        return response()->json(['message' => 'Password reset successful.']);
    }

    public function requestOtp(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999);

        PasswordResetRequest::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => Hash::make($otp),
                'is_verified' => false,
                'attempts' => 0,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'expires_at' => now()->addMinutes(15),
            ]
        );

        Mail::raw("Your OTP is: $otp", function ($message) use ($request) {
            $message->to($request->email)->subject('Your Password Reset OTP');
        });

        return response()->json(['message' => 'OTP sent to your email.']);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $reset = PasswordResetRequest::where('email', $request->email)->first();

        if (!$reset) {
            return response()->json(['error' => 'No reset request found.'], 404);
        }

        if ($reset->expires_at->isPast()) {
            return response()->json(['error' => 'OTP expired.'], 400);
        }

        if (!Hash::check($request->otp, $reset->otp)) {
            $reset->increment('attempts');
            return response()->json(['error' => 'Invalid OTP.'], 400);
        }

        $reset->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        return response()->json(['message' => 'OTP verified.']);
    }

    public function resetPasswordViaOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ]);

        $reset = PasswordResetRequest::where('email', $request->email)->first();

        if (!$reset || !$reset->is_verified) {
            return response()->json(['error' => 'OTP not verified.'], 400);
        }

        if ($reset->reset_at) {
            return response()->json(['error' => 'Password already reset.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60)
        ]);

        $reset->update(['reset_at' => now()]);

        event(new PasswordReset($user));

        return response()->json(['message' => 'Password reset successful.']);
    }
}
