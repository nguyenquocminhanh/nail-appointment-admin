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
        Schema::create('admin_update_notis', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('update_thing');
            $table->tinyInteger('is_read')->default('0')->comment('0=Not, 1=Read');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_update_notis');
    }
};
