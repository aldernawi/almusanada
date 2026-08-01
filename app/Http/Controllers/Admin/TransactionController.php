<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->latest()->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        return view('admin.transactions.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'owner_name' => 'required|string|max:255',
            'details' => 'nullable|string',
            'status' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Auto-generate sequential transaction number (e.g., MS-1, MS-2)
        $nextId = (Transaction::max('id') ?? 0) + 1;
        $transactionNumber = 'MS-' . $nextId;

        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('transactions'), $fileName);
            $pdfPath = 'transactions/' . $fileName;
        }

        $transaction = Transaction::create([
            'user_id' => $request->user_id,
            'transaction_number' => $transactionNumber,
            'owner_name' => $request->owner_name,
            'details' => $request->details,
            'status' => $request->status,
            'pdf_path' => $pdfPath,
        ]);

        return redirect()->route('admin.transactions.index')->with('success', 'تم إضافة المعاملة بنجاح ورقمها: ' . $transactionNumber);
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $customers = User::where('role', 'customer')->get();
        return view('admin.transactions.edit', compact('transaction', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'owner_name' => 'required|string|max:255',
            'details' => 'nullable|string',
            'status' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $transaction = Transaction::findOrFail($id);
        
        $data = $request->except('pdf');

        if ($request->hasFile('pdf')) {
            // Delete old PDF if exists
            if ($transaction->pdf_path && file_exists(public_path($transaction->pdf_path))) {
                unlink(public_path($transaction->pdf_path));
            }

            $file = $request->file('pdf');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('transactions'), $fileName);
            $data['pdf_path'] = 'transactions/' . $fileName;
        }

        $transaction->update($data);

        return redirect()->route('admin.transactions.index')->with('success', 'تم تحديث المعاملة بنجاح');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return redirect()->route('admin.transactions.index')->with('success', 'تم حذف المعاملة بنجاح');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);
        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->route('admin.transactions.index')->with('success', 'تم تحديث حالة المعاملة بنجاح');
    }

    // Customer Account Management
    public function customers()
    {
        $customers = User::where('role', 'customer')->get();
        return view('admin.customers.index', compact('customers'));
    }

    public function createCustomer()
    {
        return view('admin.customers.create');
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@customer.local',
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'can_view_transactions' => $request->has('can_view_transactions'),
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'تم إنشاء حساب العميل بنجاح');
    }

    public function destroyCustomer($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'تم حذف حساب العميل بنجاح');
    }

    public function editCustomer($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $validatedData = $request->validate($rules);

        $customer->name = $validatedData['name'];
        $customer->username = $validatedData['username'];

        if (!empty($validatedData['password'])) {
            $customer->password = Hash::make($validatedData['password']);
        }

        // Update view_transactions permission
        $customer->can_view_transactions = $request->has('can_view_transactions');

        $customer->save();

        return redirect()->route('admin.customers.index')->with('success', 'تم تحديث بيانات العميل بنجاح.');
    }
}
