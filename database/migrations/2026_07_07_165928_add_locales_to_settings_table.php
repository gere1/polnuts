<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->json('locales')->nullable()->after('font');
        });

        // ka is always active (it's the unprefixed default); en/de start enabled
        // to match current behavior, and can be turned off from the settings page.
        DB::table('settings')->update(['locales' => json_encode(['en', 'de'])]);
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('locales');
        });
    }
};
