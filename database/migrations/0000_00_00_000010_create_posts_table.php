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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->comment('Tên sản phẩm');
            $table->unsignedBigInteger('seller_id')->comment('ID người bán');
            $table->unsignedBigInteger('category_id')->comment('ID danh mục');
            $table->string('slug', 255)->unique()->comment('Đường dẫn thân thiện với SEO');
            $table->decimal('price', 20, 0)->comment('Giá sản phẩm');
            $table->float('area')->comment('Diện tích');
            $table->text('location')->nullable()->comment('Vị trí cụ thể');
            $table->string('address', 255)->comment('Địa chỉ');
            $table->text('description')->nullable()->comment('Mô tả chi tiết sản phẩm');
            $table->enum('status', ['0', '1'])->default('1')->comment('0: Ẩn, 1: Hiện');
            $table->timestamps();
            $table->foreign('seller_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
