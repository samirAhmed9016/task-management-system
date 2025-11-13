<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // Step 1: Send OTP to email
    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $email = $request->email;
            $otp = rand(100000, 999999);

            // Save OTP in DB
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                ['token' => Hash::make($otp), 'created_at' => Carbon::now()]
            );


            Mail::to($email)->send(new OtpMail($otp));


            return response()->json([
                'status' => true,
                'message' => 'OTP sent to your email',
                'otp' => $otp
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to send OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Step 2: Verify OTP
    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $record = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$record || !Hash::check($request->otp, $record->token)) {
                return response()->json(['message' => 'Invalid OTP'], 400);
            }

            // Check expiration (10 minutes)
            if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
                return response()->json(['status' => false, 'message' => 'OTP expired'], 400);
            }

            return response()->json(['status' => true, 'message' => 'OTP verified successfully']);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to verify OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Step 3: Reset Password

    public function resetPassword(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'email'        => 'required|email|exists:users,email',
            'otp'          => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get OTP record
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'OTP not found'
            ], 400);
        }

        // Check OTP hash
        if (!Hash::check($request->otp, $record->token)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        // Check expiration (10 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            return response()->json([
                'status' => false,
                'message' => 'OTP expired'
            ], 400);
        }

        // Update password
        User::where('email', $request->email)->update([
            'password' => bcrypt($request->new_password)
        ]);

        // Delete token after successful reset
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully'
        ]);

    } catch (Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to reset password',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
