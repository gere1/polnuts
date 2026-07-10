<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $columns = ['title', 'excerpt', 'body'];

    public function up(): void
    {
        foreach ($this->columns as $column) {
            Schema::table('articles', function (Blueprint $table) use ($column) {
                $table->renameColumn($column, "{$column}_old");
            });
        }

        foreach ($this->columns as $column) {
            Schema::table('articles', function (Blueprint $table) use ($column) {
                $table->json($column)->nullable()->after("{$column}_old");
            });
        }

        DB::table('articles')->orderBy('id')->get()->each(function ($row) {
            DB::table('articles')->where('id', $row->id)->update([
                'title' => json_encode(['ka' => $row->title_old]),
                'excerpt' => json_encode(['ka' => $row->excerpt_old]),
                'body' => json_encode(['ka' => $row->body_old]),
            ]);
        });

        foreach ($this->columns as $column) {
            Schema::table('articles', function (Blueprint $table) use ($column) {
                $table->dropColumn("{$column}_old");
            });
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $column) {
            Schema::table('articles', function (Blueprint $table) use ($column) {
                $table->text("{$column}_new")->nullable();
            });
        }

        DB::table('articles')->orderBy('id')->get()->each(function ($row) {
            DB::table('articles')->where('id', $row->id)->update([
                'title_new' => data_get(json_decode($row->title, true), 'ka'),
                'excerpt_new' => data_get(json_decode($row->excerpt, true), 'ka'),
                'body_new' => data_get(json_decode($row->body, true), 'ka'),
            ]);
        });

        foreach ($this->columns as $column) {
            Schema::table('articles', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->renameColumn('title_new', 'title');
            $table->renameColumn('excerpt_new', 'excerpt');
            $table->renameColumn('body_new', 'body');
        });
    }
};
