<?php

namespace App\Events;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MedicalAuditCompleted
{
    use Dispatchable, SerializesModels;

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
}
