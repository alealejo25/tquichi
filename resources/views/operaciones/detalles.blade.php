@extends('layouts.admin')
@section('contenido')
	<div class="row">
		<div class="col-lg-6 col-lg-6 col-lg-6 col-xs-12">
			<h3>DATOS para revisar </h3>

			@if(count($errors)>0)
			<div class="alert alert-danger" role="alert">
				<ul>
					
					@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
			@endif

			@foreach ($dato as $datos)
				<label>inicio/final:</label>:{{ $datos->fechainicio}}/{{ $datos->fechafinal}}
				<label>descripcion:</label>:{{ $datos->descripcion}}
				<label>dinero entregado al chofer:</label>:{{ $datos->dineroentregado}}
				<label>dinero entregado al chofer:</label>:{{ $datos->dineroentregado}}
				<hr style="color: #0056b2;" />
				<p><label>Proveedor:</label>{{ $datos->proveedor->nombre}}/ <label>Importe Pagado:</label> {{ $datos->importepagadoproveedor}} / <label>Importe Convenido:</label> {{ $datos->importeconvenido}}<label>precio Convenido:</label> {{ $datos->precioconvenido}}<label>saldo al proveedor:</label> {{ $datos->saldoproveedoroperacion}}<label>kilosprovistos:</label> {{ $datos->kilosprovistos}}
				<hr style="color: #0056b2;" /></p>

				<p><label>Cliente:</label>  {{ $datos->cliente->nombre}}/ <label>Importe Pagado:</label> {{ $datos->importepagadocliente}} / <label>Precio Convenido:</label> {{ $datos->importeventa}} <label>Precio de venta:</label> {{ $datos->precioventa}}<label>kilos cliente:</label> {{ $datos->kiloscliente}}<label>saldoclienteoperacion:</label> {{ $datos->saldoclienteoperacion}}	<label>kilosdiferencia:</label> {{ $datos->kilosdiferencia}}<label>saldoclienteoperacion:</label> {{ $datos->saldoclienteoperacion}}</p>
				<hr style="color: #0056b2;" />				
				<p><label>Transporte:</label>  	{{ $datos->transporte->nombre}}  / <label>Flete x Tn:</label> {{ $datos->importefletextonelada}} / <label>Importe Flete:</label> {{ $datos->totalimporteflete}} / <label>Saldo de la operacion:</label> {{ $datos->saldofleteoperacion}}<label>rendicionflete:</label> {{ $datos->rendicionflete}}<label>ganancia:</label> {{ $datos->ganancia}}</p>
				
			@endforeach 

 			
			
			<div class="Form-group">
				<br/>
				<a href="/operaciones"><button class="btn btn-success">Regresar</button></a>
			</div>

		</div>
	</div> 
@endsection