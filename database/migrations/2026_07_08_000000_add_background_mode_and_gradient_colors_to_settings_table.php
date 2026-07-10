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
        // Each column is added in its own ALTER TABLE statement (separate Schema::table
        // calls) rather than batched into one. MySQL 8's instant ADD COLUMN can corrupt
        // existing rows' default values when several columns with different defaults are
        // added in a single ALTER — splitting them avoids that.
        Schema::table('settings', function (Blueprint $table) {
            $table->string('background_mode')->default('gradient')->after('content_bg_color');
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->string('gradient_top_start')->default('#fbbf24')->after('background_mode');
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->string('gradient_top_end')->default('#f97316')->after('gradient_top_start');
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->string('gradient_bottom_start')->default('#dc2626')->after('gradient_top_end');
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->string('gradient_bottom_end')->default('#6b1d3f')->after('gradient_bottom_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'background_mode',
                'gradient_top_start',
                'gradient_top_end',
                'gradient_bottom_start',
                'gradient_bottom_end',
            ]);
        });
    }
};
