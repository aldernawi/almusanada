<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('form_submissions', 'company_id')) {
            Schema::table('form_submissions', function (Blueprint $table) {
                $table->foreignId('company_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('companies')
                    ->nullOnDelete();
            });
        }

        // Expand status enum to include audit-related states (MySQL only).
        // SQLite stores ENUM as TEXT with CHECK constraint - we recreate the column instead.
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE form_submissions MODIFY COLUMN status ENUM('pending','completed','spam','approved','rejected','incomplete') NOT NULL DEFAULT 'pending'");
        } elseif ($driver === 'sqlite') {
            // SQLite: drop CHECK constraint by recreating column as plain TEXT
            Schema::table('form_submissions', function (Blueprint $table) {
                $table->string('status_new')->default('pending')->nullable();
            });
            DB::statement('UPDATE form_submissions SET status_new = status');
            Schema::table('form_submissions', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            Schema::table('form_submissions', function (Blueprint $table) {
                $table->renameColumn('status_new', 'status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
        });

        $driver = DB::connection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE form_submissions MODIFY COLUMN status ENUM('pending','completed','spam') NOT NULL DEFAULT 'completed'");
        }
    }
};
