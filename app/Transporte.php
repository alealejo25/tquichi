<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
     protected $table="transportes";

    protected $primaryKeys='id';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email1',
        'contacto',
        'telefono_contacto',
        'cuit',
        'saldo',
        'condicion'
    ];


    public function Operacion()
    {
        return $this->hasMany('App\Operacion');
    }
    
        public function CtaCteTransporte()
    {
        return $this->hasMany('App\CtaCteTransporte');
    }
     public function OrdenPago()
    {
        return $this->hasMany('App\OrdenPago');
    }

     public function scopeSearch($query,$name)
    {
        return $query->where('nombre','LIKE',"%$name%");
    }
    //------------
}
