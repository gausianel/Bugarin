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
       Schema::create('attendances', function (Blueprint $table) {
        $table->id();

        // Relasi ke class_schedules
        $table->foreignId('class_id')
            ->constrained('class_schedules')
            ->onDelete('cascade');

        // Relasi ke users (member yang ikut kelas)
        $table->foreignId('user_id')
            ->constrained('users')
            ->onDelete('cascade');

        // Data attendance
        $table->date('date');
        $table->string('status')->default('present');
        $table->time('check_in_time')->nullable();
        $table->time('check_out_time')->nullable();
        $table->string('qr_code'); // FIX: ganti dari qr-code ke qr_code

        $table->timestamps();

        // Tracking siapa yg buat
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

        // Soft delete + siapa yg hapus
        $table->softDeletes();
        $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
