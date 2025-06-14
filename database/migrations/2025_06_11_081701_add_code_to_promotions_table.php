<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('promotions', function (Blueprint $table) {
            // Thêm cột code nếu chưa có
            if (!Schema::hasColumn('promotions', 'code')) {
                $table->string('code')->nullable()->unique()->after('title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            if (Schema::hasColumn('promotions', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
