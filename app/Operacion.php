<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operacion extends Model
{
   protected $table='operaciones';

    protected $primaryKeys='id';

    protected $fillable = [
    'fechainicio',
    'fechafinal',
    'transporte_id',
    'proveedor_id',
    'dineroentregado',
    'descripcion',
    'estado',
    'precioconvenido',
    'kilosprovistos',
    'importeconvenido',
    'cliente_id',
    'precioventa',
    'kiloscliente',
    'importeventa',
    'kilosdiferencia',
    'importefletextonelada',
    'totalimporteflete',
    'ganancia',
    'acumulado'
    ];
    public function Proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }
    public function Transporte()
    {
        return $this->belongsTo('App\Transporte');
    }
    public function Cliente()
    {
        return $this->belongsTo('App\Cliente');
    }
      public function CtaCteTransporte()
    {
        return $this->hasMany('App\CtaCteTransporte');
    }
    public function CtaCtep()
    {
        return $this->hasMany('App\CtaCtep');
    }
        public function CtaCteC()
    {
        return $this->hasMany('App\CtaCteC');
    }
}
