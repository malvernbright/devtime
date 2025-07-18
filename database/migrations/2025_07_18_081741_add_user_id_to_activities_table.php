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
        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('cascade');
        });
        
        // Assign existing activities to the default user
        $defaultUser = \App\Models\User::where('email', 'admin@devtime.com')->first();
        if ($defaultUser) {
            \DB::table('activities')->whereNull('user_id')->update(['user_id' => $defaultUser->id]);
        }
        
        // Make user_id not nullable after updating existing records
        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
