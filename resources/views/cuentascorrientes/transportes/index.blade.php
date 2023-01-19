@extends('layouts.admin')
@section('contenido')

@if(Session::has('Mensaje')){{
	
	Session::get('Mensaje')
}}
@endif
			<div class="row">
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<h3>Cuentas Corrientes Transportes </h3>
				</div>
		
			<!-- BUSCADOR DE CLIENTE-->
			{!!Form::open(['route'=>'transportes.index','method'=>'GET','class'=>'navbar-form pull-right'])!!}
				<div class="input-group">

					{!! Form::text('name',null,['class'=>'form-control','placelholder'=>'Buscar Cliente..','aria-describedby'=>'search'])!!}
					<span class="input-group-addon"  id="search"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
				</div>
			{!!Form::close()!!}
 			<!-- FIN DEL BUSCADOR-->
			</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>#</th>
					<th>Nombre</th>
					<th>Direccion</th>
					<th>Telefono</th>
					<th>Contacto</th>
					<th>Saldo</th>
					<th>Opciones</th>
				</thead>
               @foreach ($transportes as $dato)
				<tr>
					<td>{{ $dato->id}}</td>
					<td>{{ $dato->nombre}}</td>
					<td>{{ $dato->direccion}}</td>
					<td>{{ $dato->telefono}}</td>
					<td>{{ $dato->contacto}}</td>
					<td>{{ $dato->saldo}}</td>
					<td>
					<form method="post" action="{{url('cuentascorrientes/transportes/'.$dato->id) }}">
							<a href="{{url('cuentascorrientes/transportes/'.$dato->id.'/nuevocomprobante')}}"><input type="button" value="Nuevo Comprobante" class="btn btn-info">	</a>
							<a href="{{url('cuentascorrientes/transportes/'.$dato->id.'/listar')}}"><input type="button" value="Comprobantes" class="btn btn-success">	</a>
							
					</form>
					</td>
				</tr>
				
				@endforeach
			</table>
		</div>
		{{$transportes->render()}}
	</div>
</div>

@endsection
