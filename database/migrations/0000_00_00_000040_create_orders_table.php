<?php

use Illuminate\Auth\Events\Failed;
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
            $table->unsignedBigInteger('user_id')->comment('ID người dùng đặt hàng');
            $table->unsignedBigInteger('package_id')->comment('ID gói dịch vụ');
            $table->decimal('total_price', 20)->comment('Tổng giá trị đơn hàng');
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending')->comment('Trạng thái đơn hàng');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('package_id')->references('id')->on('listing_packages');
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
