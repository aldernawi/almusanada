@extends('layouts.website-app')

@section('page_title', 'Team Management')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-extrabold text-slate-800">Team Management</h1>
            <p class="text-slate-500 mt-1 font-medium">Manage team members displayed on the website</p>
            <a href="{{ route('admin.employees.create') }}"
                class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-5 py-2.5 rounded-xl transition flex items-center gap-2 text-sm hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300">
                <i class="fas fa-user-plus"></i>
                Add New Member
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl mb-6 flex items-center gap-3" style="animation: contentSlide 0.4s ease;">
                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-white text-sm"></i>
                </div>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="main-card overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Position</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Email</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($employees as $employee)
                        <tr class="table-row hover:bg-slate-50 transition">
                            <td class="px-5 py-4 bg-white text-sm">
                                <p class="text-slate-800 font-bold">{{ $employee->name }}</p>
                            </td>
                            <td class="px-5 py-4 bg-white text-sm">
                                <p class="text-slate-600">{{ $employee->position }}</p>
                            </td>
                            <td class="px-5 py-4 bg-white text-sm">
                                <p class="text-slate-600">{{ $employee->email }}</p>
                            </td>
                            <td class="px-5 py-4 bg-white text-sm text-center">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('employee.show', $employee->id) }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm transition flex items-center gap-1"><i class="fas fa-eye text-xs"></i> View</a>
                                    <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this member?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm transition flex items-center gap-1"><i class="fas fa-trash text-xs"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
