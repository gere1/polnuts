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
        // Separate Schema::table() calls per column: MySQL's instant ADD COLUMN can silently
        // write the wrong default into existing rows when columns with different defaults
        // are batched in one ALTER.
        Schema::table('row_items', function (Blueprint $table) {
            $table->string('align')->default('center')->after('image_height');
        });

        Schema::table('row_items', function (Blueprint $table) {
            $table->unsignedInteger('width')->default(100)->after('align');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('row_items', function (Blueprint $table) {
            $table->dropColumn(['align', 'width']);
        });
    }
};
