<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ctasctesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('ctasctesc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipocomprobante',20);
            $table->string('nrocomprobante',20);
            $table->date('fechaemision');
            $table->date('fechavencimiento')->nullable();
            $table->decimal('debe',10,2);
            $table->decimal('haber',10,2);
            $table->decimal('acumulado',10,2);

            $table->decimal('importesubtotal',10,2)->nullable();
            $table->decimal('iva',8,2)->nullable();
            $table->string('exento',10)->nullable();
            $table->decimal('importefinal',10,2)->nullable();

            $table->string('observacion',150)->nullable();
            $table->string('estado',30)->nullable();
            $table->integer('cliente_id')->unsigned();
            $table->integer('operacion_id')->unsigned()->nullable();
            $table->integer('factura_id')->unsigned()->nullable();
            $table->timestamps();
            
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('factura_id')->references('id')->on('ctasctesc');
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
         Schema::dropIfExists('ctasctesc');
    }
}
