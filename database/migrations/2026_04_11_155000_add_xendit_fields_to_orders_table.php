<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddXenditFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $blueprint) {
            $blueprint->string('xendit_invoice_id')->nullable()->after('payment_method');
            $blueprint->string('xendit_external_id')->nullable()->after('xendit_invoice_id');
            $blueprint->text('payment_link')->nullable()->after('xendit_external_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['xendit_invoice_id', 'xendit_external_id', 'payment_link']);
        });
    }
}
