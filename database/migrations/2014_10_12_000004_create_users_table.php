<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('email')->nullable();
            $table->string('shop_name');
            $table->longText('address');

            $table->bigInteger('grade_id')->unsigned();
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');

            $table->bigInteger('admin_id')->nullable()->unsigned();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
                
            $table->bigInteger('sale_id')->nullable()->unsigned();
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
