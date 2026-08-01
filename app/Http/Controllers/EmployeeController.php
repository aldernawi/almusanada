<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.profile', compact('employee'));
    }
}
