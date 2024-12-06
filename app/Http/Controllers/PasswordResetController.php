<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\PasswordReset;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $otp = rand(100000, 999999);

        $user = User::where('email', $request->email)->first();
        PasswordReset::where('user_id', $user->id)
            ->where('reset_code_expires_at', '>', Carbon::now())
            ->update(['reset_code_expires_at' => Carbon::now()]);

        PasswordReset::create([
            'user_id' => $user->id,
            'reset_code' => $otp,
            'reset_code_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::raw("Şifre sıfırlama kodunuz: $otp", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Parol Sıfırlamağ üçün OTP Kodu');
        });

        return response()->json(['message' => 'OTP kodu e-poçt adresinizə göndərildi.'], 200);
    }


    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $passwordReset = PasswordReset::where('user_id', User::where('email', $request->email)->first()->id)
            ->where('reset_code', $request->otp)
            ->first();

        if (!$passwordReset || Carbon::now()->isAfter($passwordReset->reset_code_expires_at)) {
            return response()->json(['error' => 'Etibarsız və ya vaxtı keçmiş OTP.'], 400);
        }

        return response()->json(['message' => 'OTP təsdiqləndi. Parol sıfırlama ekranına gedə bilərsiniz.'], 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'new_password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $passwordReset = PasswordReset::where('user_id', User::where('email', $request->email)->first()->id)
            ->where('reset_code', $request->otp)
            ->first();

        if (!$passwordReset || Carbon::now()->isAfter($passwordReset->reset_code_expires_at)) {
            return response()->json(['error' => 'Etibarsız və ya vaxtı keçmiş OTP.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        $passwordReset->delete();

        return response()->json(['message' => 'Parolunuz uğurla dəyişdirildi.'], 200);
    }
}
