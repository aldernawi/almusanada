<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $formIds = $user->forms()->pluck('id');
        
        // Load folders
        $folders = $user->folders()
            ->withCount('forms')
            ->with(['forms' => function ($query) {
                $query->withCount('submissions')
                    ->with(['folders:id,name,color', 'fields:id,form_id'])
                    ->latest();
            }])
            ->get();

        // 1. All Active Forms (Not archived, not trashed)
        $allForms = $user->forms()
            ->whereNull('archived_at')
            ->withCount('submissions')
            ->with(['folders:id,name,color', 'fields:id,form_id'])
            ->latest()
            ->get();

        // 2. Favorite Forms
        $favoriteForms = $user->forms()
            ->where('is_favorite', true)
            ->whereNull('archived_at')
            ->withCount('submissions')
            ->with(['folders:id,name,color', 'fields:id,form_id'])
            ->latest()
            ->get();

        // 3. Archived Forms
        $archivedForms = $user->forms()
            ->whereNotNull('archived_at')
            ->withCount('submissions')
            ->with(['folders:id,name,color', 'fields:id,form_id'])
            ->latest()
            ->get();

        // 4. Trashed Forms (Soft deleted)
        $trashedForms = $user->forms()
            ->onlyTrashed()
            ->withCount('submissions')
            ->with(['folders:id,name,color', 'fields:id,form_id'])
            ->latest()
            ->get();

        $totalForms = $user->forms()->count();
        $activeForms = $user->forms()->where('status', 'active')->count();
        $totalSubmissions = FormSubmission::whereIn('form_id', $formIds)->count();

        // Recent submissions for quick access
        $recentSubmissions = FormSubmission::whereIn('form_id', $formIds)
            ->with(['form:id,title', 'user:id,name'])
            ->latest('submitted_at')
            ->take(8)
            ->get();

        $pendingSubmissionsCount = FormSubmission::whereIn('form_id', $formIds)
            ->where('status', 'pending')
            ->count();

        // Chart data - submissions by day for last 7 days
        $submissionsByDay = FormSubmission::whereIn('form_id', $formIds)
            ->where('submitted_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(submitted_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chart data - submissions by form
        $submissionsByForm = FormSubmission::whereIn('form_id', $formIds)
            ->select('form_id', DB::raw('COUNT(*) as count'))
            ->groupBy('form_id')
            ->with('form:id,title')
            ->get();

        return view('dashboard', compact(
            'folders',
            'allForms',
            'favoriteForms',
            'archivedForms',
            'trashedForms',
            'totalForms',
            'activeForms',
            'totalSubmissions',
            'recentSubmissions',
            'pendingSubmissionsCount',
            'submissionsByDay',
            'submissionsByForm'
        ));
    }
}
