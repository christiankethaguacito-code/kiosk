<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('map_settings', function (Blueprint $table) {
            $table->id();
            $table->string('map_image_path')->nullable();
            $table->decimal('kiosk_x', 10, 2)->default(0);
            $table->decimal('kiosk_y', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_settings');
        Schema::dropIfExists('users');
    }
};
