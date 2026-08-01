<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->index(['form_id', 'submitted_at'], 'form_submissions_form_submitted_index');
            $table->index(['form_id', 'status', 'submitted_at'], 'form_submissions_form_status_submitted_index');
        });

        Schema::table('submission_data', function (Blueprint $table) {
            $table->index(['submission_id', 'field_id'], 'submission_data_submission_field_index');
        });
    }

    public function down(): void
    {
        Schema::table('submission_data', function (Blueprint $table) {
            $table->dropIndex('submission_data_submission_field_index');
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropIndex('form_submissions_form_submitted_index');
            $table->dropIndex('form_submissions_form_status_submitted_index');
        });
    }
};
