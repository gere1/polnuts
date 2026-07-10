<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $columns = ['title', 'subtitle', 'body'];

    public function up(): void
    {
        foreach ($this->columns as $column) {
            Schema::table('rows', function (Blueprint $table) use ($column) {
                $table->renameColumn($column, "{$column}_old");
            });
        }

        foreach ($this->columns as $column) {
            Schema::table('rows', function (Blueprint $table) use ($column) {
                $table->json($column)->nullable()->after("{$column}_old");
            });
        }

        DB::table('rows')->orderBy('id')->get()->each(function ($row) {
            DB::table('rows')->where('id', $row->id)->update([
                'title' => json_encode(['ka' => $row->title_old]),
                'subtitle' => json_encode(['ka' => $row->subtitle_old]),
                'body' => json_encode(['ka' => $row->body_old]),
            ]);
        });

        foreach ($this->columns as $column) {
            Schema::table('rows', function (Blueprint $table) use ($column) {
                $table->dropColumn("{$column}_old");
            });
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $column) {
            Schema::table('rows', function (Blueprint $table) use ($column) {
                $table->text("{$column}_new")->nullable();
            });
        }

        DB::table('rows')->orderBy('id')->get()->each(function ($row) {
            DB::table('rows')->where('id', $row->id)->update([
                'title_new' => data_get(json_decode($row->title, true), 'ka'),
                'subtitle_new' => data_get(json_decode($row->subtitle, true), 'ka'),
                'body_new' => data_get(json_decode($row->body, true), 'ka'),
            ]);
        });

        foreach ($this->columns as $column) {
            Schema::table('rows', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }

        Schema::table('rows', function (Blueprint $table) {
            $table->renameColumn('title_new', 'title');
            $table->renameColumn('subtitle_new', 'subtitle');
            $table->renameColumn('body_new', 'body');
        });
    }
};
