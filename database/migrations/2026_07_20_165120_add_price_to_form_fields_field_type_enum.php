<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            // SQLite doesn't enforce ENUM constraints, no action needed
            return;
        }
        DB::statement("ALTER TABLE form_fields MODIFY COLUMN field_type ENUM('text','textarea','email','number','date','time','select','checkbox','radio','file','scale','signature','section','image','video','html','phone','url','password','hidden','rating','price')");
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            return;
        }
        DB::statement("ALTER TABLE form_fields MODIFY COLUMN field_type ENUM('text','textarea','email','number','date','time','select','checkbox','radio','file','scale','signature','section','image','video','html','phone','url','password','hidden','rating')");
    }
};
