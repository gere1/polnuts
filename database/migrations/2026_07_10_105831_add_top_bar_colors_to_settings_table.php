<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Two separate Schema::table() calls, not one — MySQL's instant ADD COLUMN can silently
     * write the wrong default into the existing settings row when multiple columns with
     * different ->default(...) values are batched in a single ALTER (see CLAUDE.md).
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('top_bar_bg_color')->default('#1f2937')->after('footer_bg_color');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('top_bar_text_color')->default('#e5e7eb')->after('top_bar_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['top_bar_bg_color', 'top_bar_text_color']);
        });
    }
};
