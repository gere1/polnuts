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
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('show_top_bar')->default(true)->after('logo');
            $table->unsignedInteger('logo_height')->default(40)->after('show_top_bar');
            $table->string('favicon')->nullable()->after('logo_height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['show_top_bar', 'logo_height', 'favicon']);
        });
    }
};
