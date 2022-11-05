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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('accountId')->unsigned();
            $table->string ('src', 255)->default('');
            $table->string ('ext', 55)->default('');
            $table->json   ('tags')->default('[]');
            $table->boolean('isDeleted')->default(false);
            $table->integer('size')->default(0);
            $table->foreign('accountId')->references('id')->on('users');
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
        Schema::dropIfExists('assets');
    }
};
