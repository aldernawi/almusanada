<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewerController extends Controller
{
    // ==================== Admin Assignment Methods ====================

    public function assignmentIndex()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this page');
        }

        $forms = Form::with(['reviewers', 'fields'])->withCount('submissions')->get();
        $reviewers = User::where('role', 'reviewer')->get();
        $viewers = User::where('role', 'viewer')->get();

        return view('reviewer.assignment', compact('forms', 'reviewers', 'viewers'));
    }

    public function assignReviewers(Request $request, Form $form)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this page');
        }

        $validated = $request->validate([
            'reviewer_ids' => 'array',
            'reviewer_ids.*' => [Rule::exists('users', 'id')->where('role', 'reviewer')],
            'viewer_ids' => 'array',
            'viewer_ids.*' => [Rule::exists('users', 'id')->where('role', 'viewer')],
        ]);

        $reviewerIds = $validated['reviewer_ids'] ?? [];
        $viewerIds = $validated['viewer_ids'] ?? [];
        $form->reviewers()->sync(array_merge($reviewerIds, $viewerIds));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reviewers updated successfully',
            ]);
        }

        return redirect()->route('reviewer.assignment')->with('success', 'Reviewers updated successfully');
    }
}
