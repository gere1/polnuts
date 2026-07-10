<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $columns = ['title', 'meta_description'];

    public function up(): void
    {
        foreach ($this->columns as $column) {
            Schema::table('pages', function (Blueprint $table) use ($column) {
                $table->renameColumn($column, "{$column}_old");
            });
        }

        foreach ($this->columns as $column) {
            Schema::table('pages', function (Blueprint $table) use ($column) {
                $table->json($column)->nullable()->after("{$column}_old");
            });
        }

        DB::table('pages')->orderBy('id')->get()->each(function ($row) {
            DB::table('pages')->where('id', $row->id)->update([
                'title' => json_encode(['ka' => $row->title_old]),
                'meta_description' => json_encode(['ka' => $row->meta_description_old]),
            ]);
        });

        foreach ($this->columns as $column) {
            Schema::table('pages', function (Blueprint $table) use ($column) {
                $table->dropColumn("{$column}_old");
            });
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $column) {
            Schema::table('pages', function (Blueprint $table) use ($column) {
                $table->string("{$column}_new")->nullable();
            });
        }

        DB::table('pages')->orderBy('id')->get()->each(function ($row) {
            DB::table('pages')->where('id', $row->id)->update([
                'title_new' => data_get(json_decode($row->title, true), 'ka'),
                'meta_description_new' => data_get(json_decode($row->meta_description, true), 'ka'),
            ]);
        });

        foreach ($this->columns as $column) {
            Schema::table('pages', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }

        Schema::table('pages', function (Blueprint $table) {
            $table->renameColumn('title_new', 'title');
            $table->renameColumn('meta_description_new', 'meta_description');
        });
    }
};
