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
        Schema::create('walkin_orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->foreignId('seller_id')->constrained('tbl_sellers')->onDelete('cascade');

            $table->json('items');

            // Delivery & payment
            $table->enum('delivery_method', ['pickup', 'delivery']);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'e_wallet']);

            // Totals
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            // Order status, timestamps
            $table->enum('status', ['partial', 'paid'])
            ->default('paid');
            $table->enum('delivery_status', ['pending', 'paid', 'completed', 'cancelled'])
            ->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walkin_orders');
    }
};
