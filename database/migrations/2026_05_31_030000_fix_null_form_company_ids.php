<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure Almusanada company exists
        $almusanada = DB::table('companies')->where('code', 'ALM-001')->first();

        if (!$almusanada) {
            $almusanadaId = DB::table('companies')->insertGetId([
                'name' => 'مساندة',
                'code' => 'ALM-001',
                'contact_email' => 'info@almusanada.sa',
                'contact_phone' => '0112345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $almusanadaId = $almusanada->id;
        }

        // Fix forms with null company_id
        DB::table('forms')->whereNull('company_id')->update(['company_id' => $almusanadaId]);
    }

    public function down(): void
    {
        // No rollback needed
    }
};
