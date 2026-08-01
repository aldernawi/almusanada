<?php

namespace App\Providers;

use App\Events\FormSubmissionReceived;
use App\Events\MedicalAuditCompleted;
use App\Jobs\SendFormWebhook;
use App\Jobs\SendMedicalAuditNotification;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('public-forms', function (Request $request) {
            return Limit::perMinute(180)->by($request->ip());
        });

        RateLimiter::for('public-submissions', function (Request $request) {
            $form = $request->route('form');
            $formId = is_object($form) ? $form->getKey() : $form;

            return [
                Limit::perMinute(60)->by('ip:'.$request->ip()),
                Limit::perMinute(300)->by('form:'.$formId),
            ];
        });

        Event::listen(FormSubmissionReceived::class, function (FormSubmissionReceived $event) {
            if (!empty($event->form->webhook_url)) {
                SendFormWebhook::dispatch($event->form, $event->submission);
            }
        });

        Event::listen(MedicalAuditCompleted::class, function (MedicalAuditCompleted $event) {
            SendMedicalAuditNotification::dispatch($event->form, $event->submission, $event->status, $event->auditor);
        });
    }
}
