<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function search()
    {
        $user = Auth::user();
        
        // Check if user has permission to view transactions
        if (!$user->hasPermission('view_transactions')) {
            // If no permission, show only user's own transactions
            $transactions = Transaction::where('user_id', Auth::id())->latest()->get();
        } else {
            // If has permission, show all transactions
            $transactions = Transaction::with('user')->latest()->get();
        }
        
        return view('customer.search', compact('transactions'));
    }

    public function result(Request $request)
    {
        $request->validate([
            'transaction_number' => 'required|string',
        ]);

        $user = Auth::user();
        $transaction = null;
        
        // Check if user has permission to view transactions
        if (!$user->hasPermission('view_transactions')) {
            // If no permission, search only user's own transactions
            $transaction = Transaction::where('transaction_number', $request->transaction_number)
                ->where('user_id', Auth::id())
                ->first();
        } else {
            // If has permission, search all transactions
            $transaction = Transaction::with('user')
                ->where('transaction_number', $request->transaction_number)
                ->first();
        }

        // Get transactions list based on permissions
        if (!$user->hasPermission('view_transactions')) {
            $transactions = Transaction::where('user_id', Auth::id())->latest()->get();
        } else {
            $transactions = Transaction::with('user')->latest()->get();
        }

        return view('customer.search', [
            'transaction' => $transaction,
            'transactions' => $transactions,
            'searched' => true,
            'query' => $request->transaction_number,
        ]);
    }
}
