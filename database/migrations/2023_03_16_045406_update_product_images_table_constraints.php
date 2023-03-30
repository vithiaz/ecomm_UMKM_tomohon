<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->change()->onUpdate('cascade')->onDelete('cascade');
        });
    }


    public function down()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropForeign('product_id');
        });
    }
};
