<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_order_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->decimal('subtotal', 18, 2);
            $table->decimal('ppn_rate', 5, 2)->nullable();
            $table->decimal('pph_rate', 5, 2)->nullable();
            $table->decimal('ppn_amount', 18, 2)->default(0);
            $table->decimal('pph_amount', 18, 2)->default(0);
            $table->decimal('total', 18, 2);
            $table->string('status')->default('draft')->index();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('invoices'); }
};
