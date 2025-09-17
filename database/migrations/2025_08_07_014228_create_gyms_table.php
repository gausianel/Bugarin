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
        Schema::create('gyms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->text('deskripsi')->nullable();
            $table->string('url_photo')->nullable();
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
        Schema::dropIfExists('gyms');
    }
};
