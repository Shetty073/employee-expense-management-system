<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('payment_mode');
            $table->decimal('amount');
            $table->string('remark')->nullable();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('CASCADE');
            $table->timestamps();
            $table->unique(['date', 'employee_id', 'amount', 'remark']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
