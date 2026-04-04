<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('qr_code')->nullable();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->string('shoe_type');
            $table->string('shoe_brand');
            $table->foreignId('treatment_id')->constrained()->cascadeOnDelete();
            $table->string('photo_before')->nullable();
            $table->string('photo_after')->nullable();
            $table->enum('service_method', ['datang_langsung', 'pickup']);
            $table->text('pickup_address')->nullable();
            $table->dateTime('pickup_date')->nullable();
            $table->decimal('pickup_fee', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('Antrian');
            $table->enum('payment_status', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->string('payment_method')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('estimated_completion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
