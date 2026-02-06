<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.wallets.index', compact('wallets'));
    }

    public function show(Wallet $wallet)
    {
        $wallet->load(['user', 'transactions' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);
        return view('admin.wallets.show', compact('wallet'));
    }

    public function transactions()
    {
        $transactions = WalletTransaction::with(['wallet.user'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.wallets.transactions', compact('transactions'));
    }

    public function addMoney()
    {
        $users = User::select('id', 'name', 'email')->get();
        return view('admin.wallets.add-money', compact('users'));
    }

    public function storeMoney(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
        ]);

        $wallet = Wallet::firstOrCreate(
            ['user_id' => $request->user_id],
            ['balance' => 0, 'currency' => 'SAR']
        );

        $wallet->increment('balance', $request->amount);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'credit',
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.wallets.index')->with('success', 'تم إضافة الرصيد بنجاح');
    }
}