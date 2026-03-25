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
        Schema::create('listing_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('ID người dùng');
            $table->text('description')->comment('Mô tả gói');
            $table->decimal('price', 20)->comment('Giá gói');
            $table->date('expiry_date')->comment('Ngày hết hạn');
            $table->enum('package_type', ['0', '1', '2'])->default('0')->comment('0: Gói thường, 1: Gói VIP, 2: Gói SVIP');
            $table->string('package_name', 255)->comment('Tên gói');
            $table->enum('status', ['0', '1'])->default('1')->comment('0: Inactive, 1: Active');
            $table->boolean('is_featured')->default(false)->comment('Gói nổi bật');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_packages');
    }
};
