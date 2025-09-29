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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'gym_id')) {
                $table->unsignedBigInteger('gym_id')->nullable()->after('role');

                // kalau gym dihapus → gym_id jadi NULL, user nggak ikut kehapus
                $table->foreign('gym_id')
                      ->references('id')
                      ->on('gyms')
                      ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'gym_id')) {
                $table->dropForeign(['gym_id']);
                $table->dropColumn('gym_id');
            }
        });
    }
};
    