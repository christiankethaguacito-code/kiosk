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
        Schema::table('buildings', function (Blueprint $table) {
            $table->decimal('endpoint_x', 10, 3)->nullable()->after('map_y');
            $table->decimal('endpoint_y', 10, 3)->nullable()->after('endpoint_x');
            $table->string('road_connection')->nullable()->after('endpoint_y');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn(['endpoint_x', 'endpoint_y', 'road_connection']);
        });
    }
};
