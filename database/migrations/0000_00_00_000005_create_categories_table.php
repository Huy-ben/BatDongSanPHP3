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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name', 255)->unique()->comment('Tên danh mục');
            $table->text('description')->comment('Mô tả danh mục');
            $table->integer('parent_id')->nullable()->comment('ID danh mục cha');
            $table->text('image')->nullable()->comment('Ảnh danh mục');
            $table->enum('status', ['0', '1'])->default('1')->comment('0: Ẩn, 1: Hiện');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
