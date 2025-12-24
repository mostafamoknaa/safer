<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\UserVoucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Get available vouchers for user.
     */
    public function getVouchers(): JsonResponse
    {
        $vouchers = Voucher::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                    ->orWhereRaw('used_count < usage_limit');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($voucher) {
                return [
                    'id' => $voucher->id,
                    'code' => $voucher->code,
                    'title' => app()->getLocale() === 'ar' ? $voucher->title_ar : $voucher->title_en,
                    'description' => app()->getLocale() === 'ar' ? $voucher->description_ar : $voucher->description_en,
                    'type' => $voucher->type,
                    'value' => (float) $voucher->value,
                    'min_amount' => (float) $voucher->min_amount,
                    'max_discount' => $voucher->max_discount ? (float) $voucher->max_discount : null,
                    'valid_until' => $voucher->valid_until->format('Y-m-d'),
                    'usage_remaining' => $voucher->usage_limit ? ($voucher->usage_limit - $voucher->used_count) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $vouchers,
        ]);
    }

    /**
     * Get user's used vouchers.
     */
    public function getUserVouchers(): JsonResponse
    {
        $user = Auth::user();
        
        $userVouchers = UserVoucher::with('voucher', 'booking')
            ->where('user_id', $user->id)
            ->orderByDesc('used_at')
            ->get()
            ->map(function ($userVoucher) {
                return [
                    'id' => $userVoucher->id,
                    'voucher' => [
                        'code' => $userVoucher->voucher->code,
                        'title' => app()->getLocale() === 'ar' ? $userVoucher->voucher->title_ar : $userVoucher->voucher->title_en,
                    ],
                    'discount_amount' => (float) $userVoucher->discount_amount,
                    'booking_id' => $userVoucher->booking_id,
                    'used_at' => $userVoucher->used_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $userVouchers,
        ]);
    }

    /**
     * Validate voucher code and calculate discount.
     */
    public function validateVoucher(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'كود الخصم غير صحيح',
            ], 404);
        }

        if (!$voucher->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'كود الخصم منتهي الصلاحية أو غير متاح',
            ], 400);
        }

        $user = Auth::user();
        $alreadyUsed = UserVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->exists();

        if ($alreadyUsed) {
            return response()->json([
                'success' => false,
                'message' => 'تم استخدام هذا الكود من قبل',
            ], 400);
        }

        $discount = $voucher->calculateDiscount($request->amount);

        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'المبلغ أقل من الحد الأدنى المطلوب للخصم',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'voucher_id' => $voucher->id,
                'code' => $voucher->code,
                'title' => app()->getLocale() === 'ar' ? $voucher->title_ar : $voucher->title_en,
                'discount_amount' => $discount,
                'final_amount' => $request->amount - $discount,
            ],
        ]);
    }

    /**
     * Insert sample vouchers.
     */
    public function insertSampleVouchers(): JsonResponse
    {
        $vouchers = [
            [
                'code' => 'WELCOME20',
                'title_ar' => 'خصم الترحيب',
                'title_en' => 'Welcome Discount',
                'description_ar' => 'احصل على خصم 20% على حجزك الأول',
                'description_en' => 'Get 20% discount on your first booking',
                'type' => 'percentage',
                'value' => 20,
                'min_amount' => 100,
                'max_discount' => 200,
                'usage_limit' => 100,
                'used_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'SAVE50',
                'title_ar' => 'وفر 50 ريال',
                'title_en' => 'Save 50 SAR',
                'description_ar' => 'خصم 50 ريال على الحجوزات أكثر من 300 ريال',
                'description_en' => '50 SAR discount on bookings over 300 SAR',
                'type' => 'fixed',
                'value' => 50,
                'min_amount' => 300,
                'max_discount' => null,
                'usage_limit' => 50,
                'used_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'WEEKEND15',
                'title_ar' => 'خصم نهاية الأسبوع',
                'title_en' => 'Weekend Discount',
                'description_ar' => 'خصم 15% على حجوزات نهاية الأسبوع',
                'description_en' => '15% discount on weekend bookings',
                'type' => 'percentage',
                'value' => 15,
                'min_amount' => 200,
                'max_discount' => 150,
                'usage_limit' => null,
                'used_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(2),
                'is_active' => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sample vouchers inserted successfully',
        ]);
    }
}