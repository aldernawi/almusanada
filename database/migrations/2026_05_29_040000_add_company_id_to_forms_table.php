<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('forms', 'company_id')) {
            Schema::table('forms', function (Blueprint $table) {
                $table->foreignId('company_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('companies')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
        });
    }
};
