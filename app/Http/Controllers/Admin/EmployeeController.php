<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'bio' => 'nullable|string',
            'linkedin' => 'nullable|url',
        ]);

        Employee::create($request->all());

        return redirect()->route('admin.employees.index')->with('success', 'تم إضافة الموظف بنجاح');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'تم حذف الموظف بنجاح');
    }
}
