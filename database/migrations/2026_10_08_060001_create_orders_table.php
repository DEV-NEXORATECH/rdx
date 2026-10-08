<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->date('order_date');
            $table->string('shipper');
            $table->string('consignee');
            $table->string('customer');
            $table->string('destination');
            $table->string('shipping_line');
            $table->string('container_type');
            $table->string('marketing');
            $table->decimal('selling_price', 18, 2)->default(0);
            $table->decimal('capital_door', 18, 2)->default(0);
            $table->decimal('capital_of', 18, 2)->default(0);
            $table->decimal('capital_ops', 18, 2)->default(0);
            $table->decimal('capital_other', 18, 2)->default(0);
            $table->decimal('ppn_rate', 5, 2)->nullable();
            $table->decimal('pph_rate', 5, 2)->nullable();
            $table->decimal('ppn_amount', 18, 2)->default(0);
            $table->decimal('pph_amount', 18, 2)->default(0);
            $table->decimal('profit', 18, 2)->default(0);
            $table->decimal('profit_percentage', 8, 2)->default(0);
            $table->timestamps();

            $table->index(['customer', 'order_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
