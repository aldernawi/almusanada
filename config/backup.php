<?php

return [

    'backup' => [
        /*
         * The name of this application. You can use this name to monitor
         * the backups.
         */
        'name' => env('APP_NAME', 'almusanada-backup'),

        'source' => [
            'files' => [
                /*
                 * The list of directories and files that will be included in the backup.
                 */
                'include' => [
                    base_path(),
                ],

                /*
                 * These directories and files will be excluded from the backup.
                 */
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                    storage_path('logs'),
                    storage_path('framework/cache'),
                    storage_path('framework/sessions'),
                    base_path('.git'),
                ],

                /*
                 * Determines if symlinks should be followed.
                 */
                'follow_links' => false,

                /*
                 * Determines if it should avoid unreadable folders.
                 */
                'ignore_unreadable_directories' => false,

                /*
                 * This path is used to make directories in resulting zip-file relative
                 * Leave empty to make it absolute. Works only in local driver.
                 */
                'relative_path' => null,
            ],

            /*
             * The names of the connections to the databases that should be backed up
             * MySQL, PostgreSQL, SQLite and Mongo are supported.
             */
            'databases' => [
                'sqlite',
            ],
        ],

        /*
         * The database dump can be compressed to decrease diskspace usage.
         *
         * Out of the box Laravel-backup supplies
         * Spatie\DbDumper\Compressors\GzipCompressor::class.
         *
         * You can also create custom compressor.
         * More info: https://github.com/spatie/db-dumper#using-compression
         */
        'database_dump_compressor' => null,

        /*
         * The file extensions used for the database dump files.
         *
         * If not specified, the file extensions will be 
         * .sql for uncompressed dumps and .sql.gz for compressed dumps.
         */
        'database_dump_file_extension' => '',

        'destination' => [
            /*
             * The compression method to be used for creating the zip archive.
             *
             * Refer to: https://www.php.net/manual/en/ziparchive.open.php
             *
             * If not specified (or set to null), the default constant ZIPARCHIVE::CM_DEFAULT
             * will be used, which is effectively the same as creating the zip without compression.
             *
             * To create the zip with maximum compression, use: \PhpZip\Constants\ZipCompressionMethod::CM_DEFLATE.
             */
            'compression_method' => null,

            /*
             * The compression level for the database dump files.
             *
             * Refer to: https://www.php.net/manual/en/ziparchive.setarchivecomment.php
             *
             * Accepts an integer between 1 and 9, where 1 is the fastest (less compression)
             * and 9 is the slowest (most compression).
             */
            'compression_level' => 9,

            /*
             * The filename prefix used for the backup zip file.
             */
            'filename_prefix' => '',

            /*
             * The disk names on which the backups will be stored.
             */
            'disks' => [
                'local',
                // 'google-drive', // Uncomment after setting up Google Drive
                // 's3', // Uncomment for AWS S3
            ],
        ],

        /*
         * The directory where the temporary files will be stored.
         */
        'temporary_directory' => storage_path('app/backup-temp'),

        /*
         * The password to be used for archive encryption.
         * Set to null to disable encryption.
         */
        'password' => env('BACKUP_ARCHIVE_PASSWORD'),

        /*
         * The encryption algorithm to be used for archive encryption.
         * Refer to: https://www.php.net/manual/en/ziparchive.setencryptionname.php
         *
         * Accepts the following values:
         * - ZipArchive::EM_NONE (default, no encryption)
         * - ZipArchive::EM_AES_128
         * - ZipArchive::EM_AES_192
         * - ZipArchive::EM_AES_256
         */
        'encryption' => env('BACKUP_ARCHIVE_ENCRYPTION', ZipArchive::EM_AES_256),
    ],

    /*
     * You can get notified when specific events occur. Out of the box you can use 'mail' and 'slack'.
     * For Slack you need to install laravel/slack-notification-channel.
     *
     * You can also use your own notification classes, just make sure the class extends:
     * Spatie\Backup\Notifications\BaseNotification
     */
    'notifications' => [
        'notifications' => [
            \Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification::class => ['mail', 'slack'],
            \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFoundNotification::class => ['mail', 'slack'],
            \Spatie\Backup\Notifications\Notifications\CleanupHasFailedNotification::class => ['mail', 'slack'],
            \Spatie\Backup\Notifications\Notifications\BackupWasSuccessfulNotification::class => ['slack'],
            \Spatie\Backup\Notifications\Notifications\HealthyBackupWasFoundNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessfulNotification::class => ['slack'],
        ],

        /*
         * Here you can specify the notifiable to which the notifications should be sent. The default
         * notifiable will use the variables specified in this config file.
         */
        'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,

        'mail' => [
            'to' => env('BACKUP_MAIL_TO', 'admin@almusanada.com'),
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'backup@almusanada.com'),
                'name' => env('MAIL_FROM_NAME', 'Almusanada Backup'),
            ],
        ],

        'slack' => [
            'webhook_url' => env('BACKUP_SLACK_WEBHOOK_URL', ''),

            /*
             * If this is set to null the default channel of the webhook will be used.
             */
            'channel' => env('BACKUP_SLACK_CHANNEL', null),

            'username' => env('BACKUP_SLACK_USERNAME', 'Almusanada Backup Bot'),
            'icon' => env('BACKUP_SLACK_ICON', null),
        ],
    ],

    /*
     * Here you can specify which backups should be monitored.
     * If a backup does not meet the specified requirements the
     * UnHealthyBackupWasFoundEvent will be fired.
     */
    'monitor_backups' => [
        [
            'name' => env('APP_NAME', 'almusanada-backup'),
            'disks' => ['local'],
            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => 1,
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => 5000,
            ],
        ],

        /*
        [
            'name' => 'name of the second app',
            'disks' => ['local', 's3'],
            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => 1,
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => 5000,
            ],
        ],
        */
    ],

    'cleanup' => [
        /*
         * The strategy that will be used to cleanup old backups. The default strategy
         * will keep all backups for a certain amount of days. After that period only
         * a daily backup will be kept. After that period only weekly backups will
         * be kept and so on.
         *
         * You can read more about the cleanup strategy in the documentation:
         * https://docs.spatie.be/laravel-backup/v8/cleaning-up-old-backups/overview
         *
         * The available strategies are:
         * - Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class
         * - Spatie\Backup\Tasks\Cleanup\Strategies\MaximumPeriodStrategy::class
         */
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

        'default_strategy' => [
            /*
             * The number of days for which backups must be kept.
             */
            'keep_all_backups_for_days' => 7,

            /*
             * The number of days for which daily backups must be kept.
             */
            'keep_daily_backups_for_days' => 16,

            /*
             * The number of weeks for which one weekly backup must be kept.
             */
            'keep_weekly_backups_for_weeks' => 8,

            /*
             * The number of months for which one monthly backup must be kept.
             */
            'keep_monthly_backups_for_months' => 4,

            /*
             * The number of years for which one yearly backup must be kept.
             */
            'keep_yearly_backups_for_years' => 2,

            /*
             * After cleaning up the backups remove the oldest backup until
             * this amount of megabytes has been reached.
             */
            'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
        ],

        /*
         * The number of days for which backups must be kept.
         */
        'maximum_period_strategy' => [
            'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
            'delete_backups_older_than_days' => 365,
        ],
    ],
];
