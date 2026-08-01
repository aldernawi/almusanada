<?php

namespace App\Events;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FormSubmissionReceived
{
    use Dispatchable, SerializesModels;

    public Form $form;
    public FormSubmission $submission;

    public function __construct(Form $form, FormSubmission $submission)
    {
        $this->form = $form;
        $this->submission = $submission;
    }
}
