<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtaCteP extends Model
{
    protected $table="ctasctesp";

    protected $primaryKeys='id';

    protected $fillable = [
	    'tipocomprobante',
	    'nrocomprobante',
	    'fechaemision',
	    'fechavencimiento',
	    'debe',
        'haber',
        'acumulado',
	    'observacion',
	    'estado',
	    'proveedor_id',
	    'operacion_id',
	    'factura_id'

	];
	public function Proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }
      public function Operacion()
    {
        return $this->belongsTo('App\Operacion');
    }
}
