<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->text('about_text')->nullable();
            $table->string('footer_text')->nullable();
            $table->string('primary_color')->default('#007bff');
            $table->string('secondary_color')->default('#0056b3');
            $table->string('contact_email')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('service_1_title')->nullable();
            $table->text('service_1_description')->nullable();
            $table->string('service_2_title')->nullable();
            $table->text('service_2_description')->nullable();
            $table->string('service_3_title')->nullable();
            $table->text('service_3_description')->nullable();
            $table->string('font_size')->default('14px');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
