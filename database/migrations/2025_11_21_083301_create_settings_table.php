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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        
        DB::table('settings')->insert([
            ['key' => 'kiosk_x', 'value' => '195', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'kiosk_y', 'value' => '260', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'map_image_path', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
