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
        Schema::create('heads', function (Blueprint $table) {
            $table->id();                          // Primary key
            $table->string('name');                // Head's full name
            $table->string('title');               // Position (Registrar, Dean, etc.)
            $table->text('credentials')->nullable(); // Degrees, certifications, etc.
            $table->timestamps();                  // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heads');
    }
};
