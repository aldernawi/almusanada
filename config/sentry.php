<?php

return [

    // The DSN (Data Source Name) tells the SDK where to send events.
    // If you omit it, the SDK will try to read it from the SENTRY_DSN environment variable.
    // Learn more at https://docs.sentry.io/concepts/key-terms/dsn-explainer/
    'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN')),

    // The release version of your application
    // Example with git hash: trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD'))
    'release' => env('SENTRY_RELEASE', '1.0.0'),

    // The environment of your application (e.g. local, production)
    'environment' => env('APP_ENV'),

    // The sample rate for performance monitoring (0.0 - 1.0)
    // 1.0 means 100% of transactions are captured
    'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', 1.0),

    // The sample rate for profiling (0.0 - 1.0)
    'profiles_sample_rate' => (float) env('SENTRY_PROFILES_SAMPLE_RATE', 0.0),

    // Capture request body in events (can expose sensitive data)
    'send_default_pii' => env('SENTRY_SEND_DEFAULT_PII', false),

    // Before send callback - filter sensitive data
    'before_send' => function (\Sentry\Event $event): ?\Sentry\Event {
        // Filter sensitive data
        $request = $event->getRequest();
        if (isset($request['data'])) {
            // Remove sensitive fields
            $sensitiveFields = ['password', 'token', 'credit_card', 'ssn', 'auditor_notes'];
            foreach ($sensitiveFields as $field) {
                if (isset($request['data'][$field])) {
                    $request['data'][$field] = '[FILTERED]';
                }
            }
            $event->setRequest($request);
        }

        return $event;
    },

    // Max length for request bodies
    'max_request_body_size' => 'medium',

    // In-app include paths for better stack traces
    'in_app_include' => [
        base_path('app'),
        base_path('routes'),
    ],

    // Exclude paths from in-app detection
    'in_app_exclude' => [
        base_path('vendor'),
        base_path('storage'),
    ],

    // Prefix for paths in stack traces
    'prefixes' => [
        base_path(),
    ],

    // Extra context for all events
    'context_lines' => 5,
];
