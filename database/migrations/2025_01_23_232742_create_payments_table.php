<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('tbl_orders');
            $table->foreignId('customer_id')->constrained('tbl_users');
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->string('payment_method');
            $table->decimal('payment_amount', 10, 2);
            $table->string('receipt_file');
            $table->dateTime('payment_date');
            $table->string('message')->nullable();
            $table->dateTime('paid_at');
            $table->date('pickup_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_payments');
        Schema::table('tbl_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('courier_id')->nullable(false)->change();
        });
    }
};
