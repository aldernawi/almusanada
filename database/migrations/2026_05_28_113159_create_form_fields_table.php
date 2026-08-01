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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->enum('field_type', [
                'text', 'textarea', 'email', 'number', 'date', 'time',
                'select', 'checkbox', 'radio', 'file', 'scale', 'signature',
                'section', 'image', 'video', 'html', 'phone', 'url',
                'password', 'hidden', 'rating'
            ]);
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->json('options')->nullable();
            $table->string('default_value')->nullable();
            $table->boolean('required')->default(false);
            $table->integer('order')->default(0);
            $table->json('validation_rules')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
