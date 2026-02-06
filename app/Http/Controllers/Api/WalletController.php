<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Get user wallet balance and recent transactions.
     */
    public function getWallet(): JsonResponse
    {
        $user = Auth::user();
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'currency' => 'SAR']
        );

        $recentTransactions = $wallet->transactions()
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => (float) $transaction->amount,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => (float) $wallet->balance,
                'currency' => $wallet->currency,
                'recent_transactions' => $recentTransactions,
            ],
        ]);
    }

    /**
     * Get wallet transaction history.
     */
    public function getTransactions(Request $request): JsonResponse
    {
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $query = $wallet->transactions();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->orderByDesc('created_at')
            ->paginate(20)
            ->through(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => (float) $transaction->amount,
                    'description' => $transaction->description,
                    'reference_id' => $transaction->reference_id,
                    'reference_type' => $transaction->reference_type,
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * Add money to wallet.
     */
    public function addMoney(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:10000',
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0, 'currency' => 'SAR']
            );

            $wallet->increment('balance', $request->amount);

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'credit',
                'amount' => $request->amount,
                'description' => 'إضافة رصيد إلى المحفظة',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الرصيد بنجاح',
                'data' => [
                    'new_balance' => (float) $wallet->balance,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'فشل في إضافة الرصيد',
            ], 500);
        }
    }
}