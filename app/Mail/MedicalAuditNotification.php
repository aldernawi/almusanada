<?php

namespace App\Mail;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MedicalAuditNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

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

    public function envelope(): Envelope
    {
        $statusText = match($this->status) {
            'approved' => 'تمت الموافقة',
            'rejected' => 'تم الرفض',
            'incomplete' => 'ناقص المعلومات',
            default => 'تم التحديث',
        };

        return new Envelope(
            subject: "تحديث حالة المعاملة الطبية #{$this->submission->id} - {$statusText}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.medical-audit-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
