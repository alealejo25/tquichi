@extends('layouts.admin')
@section('contenido')

@if(Session::has('Mensaje')){{
	
	Session::get('Mensaje')
}}
@endif
<div class="row">
	<div class="text-center">
		<h3>Listar Movimientos</h3>
		@if(session('status'))
			@if(session('status')=='1')
				<div class="alert alert-success">
					Se Guardo el Registro!!!!		
				</div>
			@else
				<div class="alert alert-danger">
					NO GUARDO LA FINALIZACION DEL FLETE!!!!
					
				</div>
			@endif
		@endif
	</div>
</div
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado Operaciones <a href="operaciones/create"><button class="btn btn-success">Iniciar Operaciones</button></a></h3>
		
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive ">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>#</th>
					<th>Transporte</th>
					<th>Proveedor</th>
					<th>Cliente</th>
					<th>Descripcion</th>
					<th>Dinero Entregado</th>
					<th>Fecha Inicio</th>
					<th>Fecha Final</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($operaciones as $datos)
				<tr>
					<td>{{ $datos->id}}</td>
					<td>{{ $datos->transporte->nombre}}</td>
					<td>{{ $datos->proveedor->nombre}}</td>
					@if($datos->cliente_id===NULL)
						<td><p> </p></td>
					@else
						<td>{{ $datos->cliente->nombre}}</td>
					@endif

					<td>{{ $datos->descripcion}}</td>
					<td> $ {{number_format($datos->dineroentregado,2,",",".")}}</td>
					<td>{{ $datos->fechainicio}}</td>
					<td>{{ $datos->fechafinal}}</td>
					<td>{{ $datos->estado}}</td>
					<td>
					<form method="post">
						<a href="{{url('operaciones/'.$datos->id.'/finalizar')}}"><input type="button" value="Finalizar" class="btn btn-primary">	</a>
						<a href="{{url('operaciones/'.$datos->id.'/anular')}}"><input type="button"  value="Anular" class="btn btn-danger">	</a>
						<a href="{{url('operaciones/'.$datos->id.'/detalles')}}"><input type="button" value="Detalles" class="btn btn-success">	</a>
						
					</form>


					</td>
						
					</td>
				</tr>
				
				@endforeach
			</table>
		</div>
		{{$operaciones->render()}}
	</div>
	
</div>

@endsection