<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->string('order_status');
        });
    }

    public function down()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->dropColumn('order_status');
        });
    }
};