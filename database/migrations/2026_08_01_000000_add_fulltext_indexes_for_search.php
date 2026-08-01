<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * FULLTEXT indexes let MySQL/MariaDB search millions of rows quickly
     * using MATCH...AGAINST instead of a slow LIKE '%term%' table scan.
     * Requires InnoDB (MySQL 5.6+) which is the default engine for new tables.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            // FULLTEXT syntax below is MySQL/MariaDB specific; skip on other drivers (e.g. sqlite in tests).
            return;
        }

        DB::statement('ALTER TABLE submission_data ADD FULLTEXT ft_submission_data_value (value)');
        DB::statement('ALTER TABLE form_submissions ADD FULLTEXT ft_form_submissions_review_notes (review_notes)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE submission_data DROP INDEX ft_submission_data_value');
        DB::statement('ALTER TABLE form_submissions DROP INDEX ft_form_submissions_review_notes');
    }
};
