<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('refound_orders', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('user_orders')->change()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('refound_orders', function (Blueprint $table) {
            $table->dropForeign('order_id');
        });
    }
};
