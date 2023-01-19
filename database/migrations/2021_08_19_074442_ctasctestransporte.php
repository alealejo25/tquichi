<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ctasctestransporte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    Schema::create('ctasctestransporte', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipocomprobante',20);
            $table->string('nrocomprobante',20);
            $table->date('fechaemision');
            $table->date('fechavencimiento')->nullable();
            $table->decimal('debe',12,2);
            $table->decimal('haber',12,2);
            $table->decimal('acumulado',12,2);
            $table->string('observacion',150)->nullable();
            $table->string('estado',30)->nullable();
            $table->integer('transporte_id')->unsigned();
            $table->integer('operacion_id')->unsigned()->nullable();
            $table->timestamps();
            
            $table->foreign('transporte_id')->references('id')->on('transportes');
            $table->foreign('operacion_id')->references('id')->on('operaciones');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ctasctestransporte');//
    }
}
