<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Models\SubmissionData;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function usage()
    {
        $user = auth()->user();
        
        // 1. Forms count & limit
        $totalForms = $user->forms()->count();
        $formLimit = $user->form_limit ?: 10;
        $formsPercentage = min(100, round(($totalForms / $formLimit) * 100));

        // 2. Submissions count & limit
        $formIds = $user->forms()->pluck('id');
        $totalSubmissions = FormSubmission::whereIn('form_id', $formIds)->count();
        $submissionLimit = $user->submission_limit ?: 100;
        $submissionsPercentage = min(100, round(($totalSubmissions / $submissionLimit) * 100));

        // 3. Upload size & limit
        $submissionIds = FormSubmission::whereIn('form_id', $formIds)->pluck('id');
        $uploadSizeB = SubmissionData::whereIn('submission_id', $submissionIds)
            ->whereNotNull('file_data')
            ->get()
            ->sum(function($item) {
                return $item->file_data['size'] ?? 0;
            });
            
        $uploadSizeMB = round($uploadSizeB / (1024 * 1024), 2);
        $uploadLimitMB = $user->upload_limit_mb ?: 100;
        $uploadPercentage = min(100, round(($uploadSizeMB / $uploadLimitMB) * 100));

        return view('account.usage', compact(
            'totalForms', 'formLimit', 'formsPercentage',
            'totalSubmissions', 'submissionLimit', 'submissionsPercentage',
            'uploadSizeMB', 'uploadLimitMB', 'uploadPercentage'
        ));
    }

    public function settings()
    {
        return view('account.settings');
    }
}
