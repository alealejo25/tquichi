@extends('layouts.admin') 
@section('contenido')
@if(Session::has('Mensaje')){{
    
    Session::get('Mensaje')
}}
@endif
@can('administradores')
<h4>Cajas</h4>
<div class="row">
	 @foreach ($consultamovimientos2 as $consultamovimiento2)

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
             <div class="small-box bg-aqua">
                    <div class="inner">
                          <h3>$ {{ $consultamovimiento2->importe_final  }}</h3>
                          <p>Caja Local </p>
                    </div>
                    <div class="icon">
                          <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">Ultimos Movimientos <i class="fa fa-arrow-circle-right"></i></a>
              </div>
        </div>
     
   @endforeach

      <!-- ./col -->
   @foreach ($consultamovimientos1 as $consultamovimiento1)
        <div class="col-lg-3 col-xs-6">
           <!-- small box -->
              <div class="small-box bg-green">
                      <div class="inner">
                            <h3>$ {{ $consultamovimiento1->importe_final  }}</h3>
                            <p>Caja Los Altos</p>
                      </div>
                      <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                      </div>
                      <a href="#" class="small-box-footer">Ultimos Movimientos <i class="fa fa-arrow-circle-right"></i></a>
              </div>
        </div>
    @endforeach
 

         
  
</div>

@endcan









  @endsection