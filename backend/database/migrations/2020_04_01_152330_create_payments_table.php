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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('apartment_id');
            $table->unsignedBigInteger('tenant_id');
            $table->decimal('value');
            $table->date('due_at');
            $table->boolean('payed')->default(0);
            $table->timestamps();

            $table->foreign('apartment_id')
                ->references('id')
                ->on('apartments');

            $table->foreign('tenant_id')
                ->references('id')
                ->on('users');
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
