<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CompanyProfile;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index()
    {
        $profile = CompanyProfile::firstOrCreate([], [
            'company_name' => 'Almusanada',
            'hero_title' => 'Welcome to our website where creativity begins',
            'hero_description' => 'We provide you with innovative solutions and modern designs that suit your needs. We are here to turn your ideas into tangible reality with high professionalism.',
            'about_text' => 'We are a leading company in the field of information technology...',
            'primary_color' => '#007bff',
            'secondary_color' => '#0056b3',
            'font_size' => '12px',
        ]);

        $employees = \App\Models\Employee::all();
        $transactionsCount = \App\Models\Transaction::count();
        $customersCount = \App\Models\User::where('role', 'customer')->count();
        $regulationsCount = \App\Models\Regulation::count();

        return view('admin.dashboard', compact('profile', 'employees', 'transactionsCount', 'customersCount', 'regulationsCount'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'hero_title' => 'nullable|string',
            'hero_description' => 'nullable|string',
            'primary_color' => 'required|string',
            'service_1_title' => 'nullable|string|max:255',
            'service_1_description' => 'nullable|string',
            'service_2_title' => 'nullable|string|max:255',
            'service_2_description' => 'nullable|string',
            'service_3_title' => 'nullable|string|max:255',
            'service_3_description' => 'nullable|string',
            'footer_text' => 'nullable|string|max:255',
            'font_size' => 'nullable|string|in:12px,13px,14px,15px,16px,17px,18px',
        ]);

        $profile = CompanyProfile::first();
        $profile->update($request->all());

        return redirect()->back()->with('success', 'Data updated successfully');
    }
}
