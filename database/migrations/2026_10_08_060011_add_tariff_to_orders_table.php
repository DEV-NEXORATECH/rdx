<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('tariff_id')->nullable()->after('booking_number')->constrained('tariffs')->nullOnDelete();
            $table->string('origin')->nullable()->after('order_date');
        });
    }
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['tariff_id']);
            $table->dropColumn(['tariff_id', 'origin']);
        });
    }
};
