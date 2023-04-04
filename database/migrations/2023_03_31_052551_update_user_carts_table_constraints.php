<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_carts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->change()->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->change()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('user_carts', function (Blueprint $table) {
            $table->dropForeign('user_id');
            $table->dropForeign('product_id');
        });
    }
};
