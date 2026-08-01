<?php

namespace App\Mail;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionAutoresponder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Form $form;
    public FormSubmission $submission;

    public function __construct(Form $form, FormSubmission $submission)
    {
        $this->form = $form;
        $this->submission = $submission;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تم استلام طلبك - ' . $this->form->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.autoresponder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
