<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Operaciones extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('operaciones', function (Blueprint $table) {
                $table->increments('id');
                $table->date('fechainicio');
                $table->date('fechafinal')->nullable();
                $table->string('descripcion',150)->nullable();
                $table->integer('transporte_id')->unsigned()->nullable();
                $table->integer('proveedor_id')->unsigned()->nullable();
                $table->decimal('dineroentregado',12,2)->nullable();
                $table->string('estado',20)->nullable();
                $table->decimal('precioconvenido',12,2)->nullable();
                $table->decimal('kilosprovistos',12,2)->nullable();
                $table->decimal('importeconvenido',12,2)->nullable();
                $table->decimal('importepagadoproveedor',12,2)->nullable();
                $table->decimal('saldoproveedoroperacion',12,2)->nullable();
                $table->integer('cliente_id')->unsigned()->nullable();
                $table->decimal('precioventa',12,2)->nullable();
                $table->decimal('kiloscliente',12,2)->nullable();
                $table->decimal('importeventa',12,2)->nullable();
                $table->decimal('importepagadocliente',12,2)->nullable();
                $table->decimal('kilosdiferencia',12,2)->nullable();
                $table->decimal('saldoclienteoperacion',12,2)->nullable();
                $table->decimal('importefletextonelada',12,2)->nullable();
                $table->decimal('totalimporteflete',12,2)->nullable();
                $table->decimal('saldofleteoperacion',12,2)->nullable();
                $table->decimal('rendicionflete',12,2)->nullable();
                $table->decimal('ganancia',12,2)->nullable();
                $table->decimal('acumulado',12,2)->nullable();

                $table->timestamps();

                $table->foreign('transporte_id')->references('id')->on('transportes');
                $table->foreign('cliente_id')->references('id')->on('clientes');
                $table->foreign('proveedor_id')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('operaciones');//
    }
}
