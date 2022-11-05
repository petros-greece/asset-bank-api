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

//        Schema::create('tree', function (Blueprint $table) {
//            $table->id();
//            $table->json   ('categories');
//            $table->unsignedBigInteger('accountId');
//            $table->timestamps();
//        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string ('title', 255);
            $table->string ('icon', 100);
            $table->text   ('description')->default('');
            $table->integer('childrenNum')->default(0);
            $table->string ('path', 255)->default('');
            // 'test1'
            // 'petros,test1' like %test1
            // 'giorgos,petros,test1' like %test1
            $table->json   ('tags')->default('[]');
            $table->json   ('assets')->default('[]');
            $table->boolean('isDeleted')->default(false);

            $table->unsignedBigInteger('accId');

            $table->timestamps();
        });


//        Schema::create('file', function (Blueprint $table) {
//            $table->id();
//            $table->string ('src', 255);
//            $table->json   ('tags')->default('[]');
//            $table->enum   ('mime', ['image/jpg', 'image/png']);
//            $table->boolean('isDeleted')->default(false);
//            $table->unsignedBigInteger('accId');
//            $table->timestamps();
//        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
