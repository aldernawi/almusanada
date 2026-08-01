<?php

namespace App\Jobs;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendFormWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;
    public array $backoff = [10, 30, 60];

    public Form $form;
    public FormSubmission $submission;

    public function __construct(Form $form, FormSubmission $submission)
    {
        $this->form = $form;
        $this->submission = $submission;
    }

    public function handle(): void
    {
        if (empty($this->form->webhook_url)) {
            return;
        }

        $this->submission->loadMissing(['submissionData.field', 'user']);

        $values = [];
        foreach ($this->submission->submissionData as $data) {
            $label = $data->field ? $data->field->label : 'field_' . $data->field_id;
            $values[$label] = $data->value;
        }

        $payload = [
            'event' => 'form.submission.received',
            'timestamp' => now()->toIso8601String(),
            'form' => [
                'id' => $this->form->id,
                'title' => $this->form->title,
                'slug' => $this->form->slug,
            ],
            'submission' => [
                'id' => $this->submission->id,
                'submitted_at' => optional($this->submission->submitted_at)->toIso8601String(),
                'status' => $this->submission->status,
                'ip_address' => $this->submission->ip_address,
                'user' => $this->submission->user ? [
                    'id' => $this->submission->user->id,
                    'name' => $this->submission->user->name,
                    'email' => $this->submission->user->email,
                ] : null,
            ],
            'data' => $values,
        ];

        try {
            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->asJson()
                ->post($this->form->webhook_url, $payload);

            if (!$response->successful()) {
                Log::warning('Webhook returned non-success status', [
                    'form_id' => $this->form->id,
                    'submission_id' => $this->submission->id,
                    'status' => $response->status(),
                    'url' => $this->form->webhook_url,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Webhook delivery failed', [
                'form_id' => $this->form->id,
                'submission_id' => $this->submission->id,
                'url' => $this->form->webhook_url,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
