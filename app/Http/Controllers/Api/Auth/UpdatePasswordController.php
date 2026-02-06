<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UpdatePasswordController extends Controller
{
    /**
     * Update user password after verifying old password.
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'old_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user = $request->user();

            // Verify old password
            if (!Hash::check($validated['old_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'كلمة المرور القديمة غير صحيحة',
                ], 401);
            }

            // Update to new password
            $user->password = Hash::make($validated['new_password']);
            $user->save();

            // Optionally, revoke all tokens to force re-login
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث كلمة المرور بنجاح',
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
                'message' => 'حدث خطأ أثناء تحديث كلمة المرور',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
