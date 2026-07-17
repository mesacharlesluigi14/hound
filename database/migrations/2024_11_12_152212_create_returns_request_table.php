<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('user_id');
            $table->string('prod_id');
            $table->string('qty');
            $table->string('return_reason');
            $table->string('images')->nullable();
            $table->tinyInteger('return_status')->default('0');
            $table->longText('comment')->nullable();
            $table->timestamps();
        });
    }
}
