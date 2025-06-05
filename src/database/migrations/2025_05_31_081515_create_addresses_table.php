<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーと紐づく
            $table->unsignedBigInteger('item_id')->nullable(); // 商品ごとの配送先にも対応
            $table->string('postal_code');
            $table->string('address');
            $table->string('building')->nullable(); // 建物名・部屋番号など
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
