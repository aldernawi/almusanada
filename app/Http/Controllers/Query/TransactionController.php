<?php

namespace App\Http\Controllers\Query;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user')->latest();

        // Search by transaction number
        if ($request->filled('transaction_number')) {
            $query->where('transaction_number', 'like', '%' . $request->transaction_number . '%');
        }

        // Search by owner name
        if ($request->filled('owner_name')) {
            $query->where('owner_name', 'like', '%' . $request->owner_name . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20);
        $customers = User::where('role', 'customer')->pluck('name', 'id');
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];

        return view('query.transactions.index', compact('transactions', 'customers', 'statuses'));
    }

    public function show($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        return view('query.transactions.show', compact('transaction'));
    }
}
