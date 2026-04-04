<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyOrdersServiceMethodAndAddDelivery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY service_method VARCHAR(255) NOT NULL DEFAULT 'datang_langsung'");

        Schema::table('orders', function (Blueprint $table) {
            $table->text('delivery_address')->nullable()->after('pickup_date');
            $table->dateTime('delivery_date')->nullable()->after('delivery_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_address', 'delivery_date']);
        });
    }
}
