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
        Schema::create('member__gyms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('gym_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('active');

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('gym_id')->references('id')->on('gyms')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('membership__packages')->onDelete('set null');
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
        Schema::dropIfExists('member__gyms');
    }
};
