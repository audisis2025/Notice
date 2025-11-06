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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('order_code')->unique();
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_email')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'ready', 'delivered', 'cancelled'])->default('pending');
            $table->json('products')->nullable();
            $table->string('qr_path')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
