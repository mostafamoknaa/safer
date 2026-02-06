<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    /**
     * Verify OTP and complete registration.
     */
    public function verify(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'string', 'email'],
                'otp' => ['required', 'string', 'size:6'],
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير موجود',
                ], 404);
            }

            // Check if OTP matches and is not expired
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

            // Mark email as verified and clear OTP
            $user->email_verified_at = now();
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            // Create authentication token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'تم التحقق من البريد الإلكتروني بنجاح',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'image' => $user->image,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
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
                'message' => 'حدث خطأ أثناء التحقق',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resend OTP to user's email.
     */
    public function resend(Request $request): JsonResponse
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

            // Check if email is already verified
            if ($user->email_verified_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'البريد الإلكتروني تم التحقق منه بالفعل',
                ], 400);
            }

            // Generate new OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->otp_code = $otp;
            $user->otp_expires_at = now()->addMinutes(10);
            $user->save();

            // Send OTP via email
            Mail::to($user->email)->send(new OtpMail($otp));

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رمز التحقق بنجاح',
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
}
