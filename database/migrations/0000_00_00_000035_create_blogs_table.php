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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('ID người dùng tạo bài viết');
            $table->string('title', 255)->comment('Tiêu đề bài viết');
            $table->string('slug', 255)->unique()->comment('Đường dẫn thân thiện với SEO');
            $table->text('content')->comment('Nội dung bài viết');
            $table->string('image')->nullable()->comment('Hình ảnh đại diện của bài viết');
            $table->text('excerpt')->nullable()->comment('Trích đoạn ngắn của bài viết');
            $table->enum('status', ['draft', 'published'])->default('draft')->comment('Trạng thái bài viết');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
