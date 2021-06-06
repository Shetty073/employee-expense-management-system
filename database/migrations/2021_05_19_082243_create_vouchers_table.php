<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('special_remark')->nullable();
            $table->string('number')->nullable();
            $table->date('approval_date')->nullable();
            $table->decimal('approved_amount')->nullable();
            $table->integer('status')->default(0);
            $table->integer('payment_mode_draft')->nullable();
            $table->date('payment_date_draft')->nullable();
            $table->string('payment_remark_draft')->nullable();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('CASCADE');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('CASCADE');
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
        Schema::dropIfExists('vouchers');
    }
}
