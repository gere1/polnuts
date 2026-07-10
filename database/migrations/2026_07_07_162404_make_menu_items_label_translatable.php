<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->renameColumn('label', 'label_old');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->json('label')->nullable()->after('label_old');
        });

        DB::table('menu_items')->orderBy('id')->get()->each(function ($row) {
            DB::table('menu_items')->where('id', $row->id)->update([
                'label' => json_encode(['ka' => $row->label_old]),
            ]);
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('label_old');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('label_new')->nullable();
        });

        DB::table('menu_items')->orderBy('id')->get()->each(function ($row) {
            DB::table('menu_items')->where('id', $row->id)->update([
                'label_new' => data_get(json_decode($row->label, true), 'ka'),
            ]);
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('label');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->renameColumn('label_new', 'label');
        });
    }
};
