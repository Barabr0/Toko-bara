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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained()
                  ->restrictOnDelete();

            $table->string('product_name');
            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 15, 2);
              $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');

                $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('unpaid');

                $table->string('shipping_name');
                $table->string('shipping_address');
                $table->string('shipping_phone');

                $table->decimal('total_amount', 12, 2);
                $table->decimal('shipping_cost', 12, 2)->default(0);

                $table->string('snap_token')->nullable();
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
