<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('status')->default('draft')->index();
            $table->date('scheduled_date')->nullable();
            $table->string('vehicle')->nullable();
            $table->string('driver')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique('order_id');
        });
    }

    public function down(): void { Schema::dropIfExists('job_orders'); }
};
