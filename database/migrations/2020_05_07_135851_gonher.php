<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gonher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('gonher', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku', 20);
            $table->string('titulo', 200);
            $table->string('marca', 50);
            $table->text('descripcion_corta');
            $table->text('descripcion_completa');
            $table->text('imagen');
            $table->json('imgs');
            $table->json('categorias');
            $table->decimal('precio', 8, 4);
            $table->decimal('precio_dis', 8, 4);
            $table->decimal('precio_desc', 8, 4);
            $table->enum('disponible', ["si", "no"]);
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
        //
    }
}
