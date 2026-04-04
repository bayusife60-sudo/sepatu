<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('expense_category_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('proof_of_payment')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Admin who inputted
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
        Schema::dropIfExists('expenses');
    }
}
