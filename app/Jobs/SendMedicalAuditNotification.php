<?php

namespace App\Jobs;

use App\Mail\MedicalAuditNotification;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMedicalAuditNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    public Form $form;
    public FormSubmission $submission;
    public string $status;
    public User $auditor;

    public function __construct(Form $form, FormSubmission $submission, string $status, User $auditor)
    {
        $this->form = $form;
        $this->submission = $submission;
        $this->status = $status;
        $this->auditor = $auditor;
    }

    public function handle(): void
    {
        if (!$this->submission->company || !$this->submission->company->contact_email) {
            Log::warning('No contact email for company', [
                'submission_id' => $this->submission->id,
                'company_id' => $this->submission->company_id,
            ]);
            return;
        }

        try {
            Mail::to($this->submission->company->contact_email)
                ->send(new MedicalAuditNotification(
                    $this->form,
                    $this->submission,
                    $this->status,
                    $this->auditor
                ));
        } catch (\Throwable $e) {
            Log::error('Failed to send medical audit notification', [
                'submission_id' => $this->submission->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
