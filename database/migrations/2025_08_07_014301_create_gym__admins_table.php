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
        Schema::create('gym__admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role_in_gym');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            $table->foreign('gym_id')->references('id')->on('gyms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            
            // tracking siapa yg buat
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            // soft delete (deleted_at) + siapa yg hapus
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym__admins');
    }
};
