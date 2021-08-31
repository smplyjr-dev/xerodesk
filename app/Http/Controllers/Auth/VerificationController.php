<?php

namespace App\Http\Controllers\Auth;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:60,1')->only('verify', 'resend');
    }

    /**
     * Mark the user's email address as verified.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request, User $user)
    {
        if (!URL::hasValidSignature($request)) {
            throw ValidationException::withMessages([
                'general' => ['invalid', trans('verification.invalid')]
            ]);
        }

        // when your account is already verified, there's no need to re-verify it
        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'general' => ['already_verified', trans('verification.already_verified')]
            ]);
        }

        $user->markEmailAsVerified();
        $user->status = 1;
        $user->save();

        event(new Verified($user));

        return response()->json([
            'status' => trans('verification.verified')
        ], 200);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // when email not found
        if (is_null($user)) {
            throw ValidationException::withMessages([
                'email' => trans('verification.user')
            ]);
        }

        // when your account is already verified, there's no reason for another verification link
        if ($user->hasVerifiedEmail()) {
            if ($user->email === auth()->user()->email) {
                throw ValidationException::withMessages([
                    'email' => [trans('verification.already_verified')]
                ]);
            } else {
                throw ValidationException::withMessages([
                    'email' => [
                        $user->bio->last_name . ', ' . $user->bio->first_name . ' account is already verified.'
                    ]
                ]);
            }
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => [
            'A verification code has been sent to <strong>' . $user->email . '</strong>.',
            'Please verify your account as soon as possible.'
        ], 200]);
    }
}
