<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('rows')->where('type', 'columns')->update(['type' => 'text']);
    }

    public function down(): void
    {
        DB::table('rows')->where('type', 'text')
            ->whereNotNull('settings')
            ->where('settings->columns', '>', 1)
            ->update(['type' => 'columns']);
    }
};
