<?php

use App\Models\Rider;
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
        Schema::create('tbl_riders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('tbl_sellers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('tbl_users')->onDelete('cascade');
            $table->string('document_file')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_riders');
    }
};
