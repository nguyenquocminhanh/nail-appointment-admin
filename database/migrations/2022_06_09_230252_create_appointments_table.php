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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('date');
            $table->string('time');
            $table->string('services');

            $table->string('name');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default('0')->comment('0=Pending, 1=Visited');
            $table->tinyInteger('is_user_read')->default('0')->comment('0=Not, 1=Read');
            $table->integer('user_noti_id');
            $table->tinyInteger('is_admin_read')->default('0')->comment('0=Not, 1=Read');
            $table->integer('admin_noti_id');
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
        Schema::dropIfExists('appointments');
    }
};
