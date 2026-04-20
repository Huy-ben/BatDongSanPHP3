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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // user mua gói
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // gói dịch vụ
            // Loại giao dịch: mua mới / gia hạn / nâng cấp
            $table->enum('type', ['new', 'renew'])->default('new');

            // Lưu tên gói tại thời điểm mua
            $table->string('package_name');

            // số tiền
            $table->decimal('amount', 12, 2)->default(0);

            // mã giao dịch từ cổng
            $table->string('transaction_code')->nullable();

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
