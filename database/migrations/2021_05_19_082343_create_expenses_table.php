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
            $table->foreignId('category_id')->nullable()->constrained('expense_categories')->onDelete('restrict');
            $table->string('description');
            $table->string('bill')->nullable();
            $table->decimal('amount');
            $table->decimal('approved_amount')->nullable();
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->onDelete('CASCADE');
            $table->string('remark')->nullable();
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
