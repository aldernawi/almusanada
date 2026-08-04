<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->boolean('can_view_transactions')->default(false)->after('role');
            $table->boolean('can_view_all_transactions')->default(false)->after('can_view_transactions');
        });

        // MySQL/MariaDB need the enum expanded; sqlite tests keep the existing string column.
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'reviewer', 'viewer', 'user', 'customer', 'query_user') DEFAULT 'user'");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'reviewer', 'user') DEFAULT 'user'");
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'can_view_transactions', 'can_view_all_transactions']);
        });
    }
};
