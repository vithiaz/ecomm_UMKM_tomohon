<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->foreign('buyer_id')->references('id')->on('users')->change()->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('umkm_id')->references('id')->on('umkms')->change()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->dropForeign('buyer_id');
            $table->dropForeign('umkm_id');
        });
    }
};
