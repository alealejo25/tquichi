<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtaCteTransporte extends Model
{
    protected $table="ctasctestransporte";

    protected $primaryKeys='id';

    protected $fillable = [
        'tipocomprobante',
        'nrocomprobante',
        'fechaemision',
        'debe',
        'haber',
        'acumulado',
        'observacion',
        'estado',
        'transporte_id',
        'operacion_id'        
    ];
    public function Transporte()
    {
        return $this->belongsTo('App\Transporte');
    }
     public function Operacion()
    {
        return $this->belongsTo('App\Operacion');
    }

}
