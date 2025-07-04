<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
    $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
    $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
    $table->timestamps();
            // Liên kết khoá ngoại
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Tránh trùng dữ liệu
            $table->unique(['category_id', 'product_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('category_product');
    }
};
