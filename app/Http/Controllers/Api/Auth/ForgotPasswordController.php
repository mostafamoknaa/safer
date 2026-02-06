<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Send OTP to user's email for password reset.
     */
    public function sendOtp(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'string', 'email'],
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير موجود',
                ], 404);
            }

            // Generate OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->otp_code = $otp;
            $user->otp_expires_at = now()->addMinutes(10);
            $user->save();

            // Send OTP via email
            Mail::to($user->email)->send(new OtpMail($otp));

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل التحقق من البيانات',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال رمز التحقق',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset password using OTP.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'string', 'email'],
                'otp' => ['required', 'string', 'size:6'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير موجود',
                ], 404);
            }

            // Verify OTP
            if ($user->otp_code !== $validated['otp']) {
                return response()->json([
                    'success' => false,
                    'message' => 'رمز التحقق غير صحيح',
                ], 401);
            }

            if ($user->otp_expires_at < now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'رمز التحقق منتهي الصلاحية',
                ], 401);
            }

            // Update password and clear OTP
            $user->password = Hash::make($validated['new_password']);
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            // Revoke all tokens to force re-login
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم إعادة تعيين كلمة المرور بنجاح',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل التحقق من البيانات',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إعادة تعيين كلمة المرور',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
