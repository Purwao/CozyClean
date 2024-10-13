<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('users_id'); // Foreign key
            $table->date('order_date') ;
            $table->integer("status");
            $table->double('total', 8, 2); 
            $table->double('paid', 8, 2)->nullable();
            $table->double('change', 8, 2)->nullable();
            $table->timestamps(); 

            // Define foreign key constraint
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
