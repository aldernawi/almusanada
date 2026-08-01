<?php

namespace App\Http\Controllers\Query;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTransactions = Transaction::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $recentTransactions = Transaction::with('user')->latest()->take(10)->get();
        
        // Statistics by status
        $statusStats = [
            'Pending' => Transaction::where('status', 'pending')->count(),
            'Processing' => Transaction::where('status', 'processing')->count(),
            'Completed' => Transaction::where('status', 'completed')->count(),
            'Cancelled' => Transaction::where('status', 'cancelled')->count(),
        ];

        return view('query.dashboard', compact(
            'totalTransactions',
            'totalCustomers',
            'recentTransactions',
            'statusStats'
        ));
    }
}
