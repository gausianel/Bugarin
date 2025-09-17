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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_gym_id')->constrained('member_gyms')->onDelete('cascade');

            $table->string('payment_method'); // cash, transfer, midtrans, dll
            $table->decimal('amount', 10, 2);
            $table->timestamp('payment_date')->nullable();
            $table->string('status')->default('pending'); // pending, paid, failed, refunded

            // ✅ tambahan
            $table->string('transaction_id')->nullable();   // dari gateway
            $table->string('invoice_number')->nullable();   // internal invoice
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();

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
        Schema::dropIfExists('payments');
    }
};
