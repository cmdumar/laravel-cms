<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if the temp table already exists
        if (!Schema::hasTable('__temp__media_files')) {
            // Create a temporary table with the new column structure
            Schema::create('__temp__media_files', function (Blueprint $table) {
                $table->id();
                $table->string('file_path')->nullable(false); // Making file_path not nullable
                $table->timestamps();
            });

            // Copy data from the original table to the new table
            DB::table('__temp__media_files')->insert(
                DB::table('media_files')->get()->toArray()
            );

            // Drop the old table
            Schema::drop('media_files');

            // Rename the temporary table to the original table name
            Schema::rename('__temp__media_files', 'media_files');
        }
    }

    public function down()
    {
        // Check if the temp table already exists
        if (!Schema::hasTable('__temp__media_files')) {
            // Create the table back with the original structure (nullable file_path)
            Schema::create('__temp__media_files', function (Blueprint $table) {
                $table->id();
                $table->string('file_path')->nullable(); // Making file_path nullable again
                $table->timestamps();
            });

            // Copy data from the current media_files table back to the temporary table
            DB::table('__temp__media_files')->insert(
                DB::table('media_files')->get()->toArray()
            );

            // Drop the current media_files table
            Schema::drop('media_files');

            // Rename the temporary table to the original table name
            Schema::rename('__temp__media_files', 'media_files');
        }
    }
};
