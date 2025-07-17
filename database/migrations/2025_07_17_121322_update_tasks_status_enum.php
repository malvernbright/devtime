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
        // For SQLite, we need to recreate the table to change the enum constraint
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status', ['todo', 'in_progress', 'review', 'completed'])->default('todo')->after('description');
        });
    }
};
