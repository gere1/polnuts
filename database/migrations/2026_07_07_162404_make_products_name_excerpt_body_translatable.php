<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $columns = ['name', 'excerpt', 'body'];

    public function up(): void
    {
        foreach ($this->columns as $column) {
            Schema::table('products', function (Blueprint $table) use ($column) {
                $table->renameColumn($column, "{$column}_old");
            });
        }

        foreach ($this->columns as $column) {
            Schema::table('products', function (Blueprint $table) use ($column) {
                $table->json($column)->nullable()->after("{$column}_old");
            });
        }

        DB::table('products')->orderBy('id')->get()->each(function ($row) {
            DB::table('products')->where('id', $row->id)->update([
                'name' => json_encode(['ka' => $row->name_old]),
                'excerpt' => json_encode(['ka' => $row->excerpt_old]),
                'body' => json_encode(['ka' => $row->body_old]),
            ]);
        });

        foreach ($this->columns as $column) {
            Schema::table('products', function (Blueprint $table) use ($column) {
                $table->dropColumn("{$column}_old");
            });
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $column) {
            Schema::table('products', function (Blueprint $table) use ($column) {
                $table->text("{$column}_new")->nullable();
            });
        }

        DB::table('products')->orderBy('id')->get()->each(function ($row) {
            DB::table('products')->where('id', $row->id)->update([
                'name_new' => data_get(json_decode($row->name, true), 'ka'),
                'excerpt_new' => data_get(json_decode($row->excerpt, true), 'ka'),
                'body_new' => data_get(json_decode($row->body, true), 'ka'),
            ]);
        });

        foreach ($this->columns as $column) {
            Schema::table('products', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name_new', 'name');
            $table->renameColumn('excerpt_new', 'excerpt');
            $table->renameColumn('body_new', 'body');
        });
    }
};
