<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Folders table
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('color')->default('#6366f1');
            $table->timestamps();
        });

        // 2. Form_Folder pivot table
        Schema::create('form_folder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->foreignId('folder_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 3. API Keys table
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('key')->unique();
            $table->string('permissions')->default('read_only'); // read_only, full_access
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        // 4. Add columns to forms table
        Schema::table('forms', function (Blueprint $table) {
            $table->boolean('is_favorite')->default(false);
            $table->timestamp('archived_at')->nullable();
        });

        // 5. Add subscription limits to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('form_limit')->default(10);
            $table->integer('submission_limit')->default(100);
            $table->integer('upload_limit_mb')->default(100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['form_limit', 'submission_limit', 'upload_limit_mb']);
        });

        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn(['is_favorite', 'archived_at']);
        });

        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('form_folder');
        Schema::dropIfExists('folders');
    }
};
