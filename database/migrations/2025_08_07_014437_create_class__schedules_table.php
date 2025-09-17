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
        Schema::create('class__schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_id');
            $table->string('class_name');
            $table->string('instructor_name')->nullable();
            $table->time('day');
            $table->time('time');
            $table->integer('quota');
            $table->foreign('gym_id')->references('id')->on('gyms')->onDelete('cascade');
            $table->timestamps();

            
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
        Schema::dropIfExists('class__schedules');
    }
};
