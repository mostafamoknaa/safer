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

class RegisterController extends Controller
{
    /**
     * Handle user registration.
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['required', 'string', 'max:20', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]);

            // Generate OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'is_admin' => false,
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);

            // Send OTP via email
            Mail::to($user->email)->send(new OtpMail($otp));

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الحساب بنجاح. يرجى التحقق من بريدك الإلكتروني لإدخال رمز التحقق',
                'data' => [
                    'email' => $user->email,
                ],
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل التحقق من البيانات',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الحساب',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
