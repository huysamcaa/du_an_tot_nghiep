<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      Schema::create('attribute_values', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('hex')->nullable();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::table('attribute_values', function (Blueprint $table) {
            $table->string('hex')->nullable(false)->change();
        });
    }
};