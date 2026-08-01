<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Throwable;

class ErrorAlertNotification extends Notification
{
    use Queueable;

    private Throwable $exception;
    private array $context;

    /**
     * Create a new notification instance.
     */
    public function __construct(Throwable $exception, array $context = [])
    {
        $this->exception = $exception;
        $this->context = $context;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['mail'];
        
        // Add Slack if webhook is configured
        if (config('services.slack.webhook_url')) {
            $channels[] = 'slack';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $errorCode = $this->generateErrorCode();
        
        return (new MailMessage)
            ->error()
            ->subject("🚨 خطأ في النظام - {$errorCode}")
            ->greeting('خطأ حرج في نظام التدقيق الطبي')
            ->line("**نوع الخطأ:** {$this->exception->getMessage()}")
            ->line("**الملف:** {$this->exception->getFile()}:{$this->exception->getLine()}")
            ->line("**الوقت:** " . now()->format('Y-m-d H:i:s'))
            ->line("**رمز الخطأ:** {$errorCode}")
            ->when(isset($this->context['user']), function (MailMessage $mail) {
                $user = $this->context['user'];
                return $mail->line("**المستخدم:** {$user->name} ({$user->email})");
            })
            ->when(isset($this->context['url']), function (MailMessage $mail) {
                return $mail->line("**الرابط:** {$this->context['url']}");
            })
            ->line('**التتبع:**')
            ->line('```')
            ->line($this->getFilteredTrace())
            ->line('```')
            ->action('عرض في Sentry', config('sentry.dsn') ? 'https://sentry.io' : url('/'))
            ->line('يرجى مراجعة الخطأ فوراً والعمل على إصلاحه.');
    }

    /**
     * Get the Slack representation of the notification.
     */
    public function toSlack(object $notifiable): SlackMessage
    {
        $errorCode = $this->generateErrorCode();
        $severity = $this->getSeverityLevel();
        
        return (new SlackMessage)
            ->error()
            ->from('Almusanada Error Bot', ':ambulance:')
            ->to(config('services.slack.channel', '#errors'))
            ->content("🚨 *خطأ {$severity} في نظام التدقيق الطبي* - {$errorCode}")
            ->attachment(function ($attachment) use ($errorCode) {
                $attachment->title($this->exception->getMessage())
                    ->fields([
                        'رمز الخطأ' => $errorCode,
                        'الملف' => basename($this->exception->getFile()) . ':' . $this->exception->getLine(),
                        'الوقت' => now()->format('Y-m-d H:i:s'),
                        'البيئة' => config('app.env'),
                    ])
                    ->when(isset($this->context['user']), function ($attachment) {
                        $user = $this->context['user'];
                        $attachment->field('المستخدم', "{$user->name} ({$user->email})");
                    })
                    ->when(isset($this->context['url']), function ($attachment) {
                        $attachment->field('الرابط', $this->context['url']);
                    })
                    ->footer('Almusanada Medical Auditing System')
                    ->timestamp(now());
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->exception->getMessage(),
            'file' => $this->exception->getFile(),
            'line' => $this->exception->getLine(),
            'trace' => $this->getFilteredTrace(),
            'context' => $this->context,
            'error_code' => $this->generateErrorCode(),
        ];
    }

    /**
     * Generate a unique error code for tracking.
     */
    private function generateErrorCode(): string
    {
        return 'ERR-' . strtoupper(substr(md5(
            $this->exception->getMessage() . 
            $this->exception->getFile() . 
            $this->exception->getLine()
        ), 0, 8));
    }

    /**
     * Get severity level based on exception type.
     */
    private function getSeverityLevel(): string
    {
        $class = get_class($this->exception);
        
        // Critical errors - immediate attention required
        $criticalErrors = [
            'Illuminate\Database\QueryException',
            'PDOException',
            'Symfony\Component\HttpKernel\Exception\HttpException',
        ];
        
        // High priority - medical claim related errors
        if (str_contains($this->exception->getMessage(), 'medical') ||
            str_contains($this->exception->getFile(), 'Medical')) {
            return 'حرج (طبي)';
        }
        
        foreach ($criticalErrors as $error) {
            if ($class === $error || is_subclass_of($this->exception, $error)) {
                return 'حرج';
            }
        }
        
        return 'متوسط';
    }

    /**
     * Get filtered stack trace (remove sensitive info).
     */
    private function getFilteredTrace(): string
    {
        $trace = $this->exception->getTraceAsString();
        
        // Limit trace length
        $trace = substr($trace, 0, 2000);
        
        // Hide sensitive paths
        $trace = str_replace(base_path(), '[APP_ROOT]', $trace);
        
        return $trace;
    }
}
