<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if reviewer can view all transactions
        if (!$user->hasPermission('view_all_transactions')) {
            // If no permission, show only user's own transactions
            $transactions = Transaction::where('user_id', Auth::id())->latest()->get();
            $stats = [
                'total' => $transactions->count(),
                'pending' => $transactions->where('status', 'pending')->count(),
                'processing' => $transactions->where('status', 'processing')->count(),
                'completed' => $transactions->where('status', 'completed')->count(),
                'cancelled' => $transactions->where('status', 'cancelled')->count(),
            ];
        } else {
            // If has permission, show all transactions
            $transactions = Transaction::with('user')->latest()->get();
            $stats = [
                'total' => $transactions->count(),
                'pending' => $transactions->where('status', 'pending')->count(),
                'processing' => $transactions->where('status', 'processing')->count(),
                'completed' => $transactions->where('status', 'completed')->count(),
                'cancelled' => $transactions->where('status', 'cancelled')->count(),
            ];
        }

        return view('reviewer.dashboard', compact('transactions', 'stats'));
    }
}
