<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
         Schema::table('coupons', function (Blueprint $table) {
             $table->integer('max_usage_per_user')->nullable(); // Adjust the type and nullability as needed
         });
    }

    public function down()
    {
         Schema::table('coupons', function (Blueprint $table) {
             $table->dropColumn('max_usage_per_user');
         });
    }
};
