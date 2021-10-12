@extends('layouts.admin')
@section('contenido')
	<div class="row">
		<div class="col-lg-6 col-lg-6 col-lg-6 col-xs-12">
			<h3>Ingreso de datos del flete</h3>

			@if(count($errors)>0)
			<div class="alert alert-danger" role="alert">
				<ul>
					
					@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
			@endif

<!-- 			@foreach ($operaciones as $datos)
				

				
			@endforeach
			@foreach ($operaciones as $datos)
				
				<p><label>Proveedor:</label>{{ $datos->proveedor->nombre}}/ <label>Precio Convenido:</label> {{ $datos->precioconvenido}} / <label>Kilos Provistos:</label> {{ $datos->kilosprovistos}} / <label>Importe Pagado:</label> {{ $datos->importepagadoproveedor}} / <label>Importe total:</label> {{ $datos->importeconvenido}}/ <label>Saldo Operacion:</label> {{ $datos->saldoproveedoroperacion}}/ <label>Saldo:</label> {{ $datos->proveedor->saldo}}</p>
				<hr style="color: #0056b2;" />
				<p><label>Cliente:</label>  / <label>Precio Convenido:</label> {{ $datos->precioventa}} / <label>Kilos Entregados:</label> {{ $datos->kiloscliente}} / <label>Importe Venta:</label> {{ $datos->importeventa}} / <label>Importe Pagado:</label> {{ $datos->importepagadocliente}} / <label>Saldo Operacion:</label> {{ $datos->saldoclienteoperacion}}</p>
				<hr style="color: #0056b2;" />				
				<p><label>Transporte:</label>  	{{ $datos->transporte->nombre}}  / <label>Saldo:</label> {{ $datos->transporte->saldo}}</p>
				<hr style="color: #0056b2;" />	
				<p><label>Kilos de Diferencia:</label>  	{{ $datos->kilosdiferencia}}  </p>
			@endforeach -->

 			{!!Form::open(array('url'=>'operaciones/guardaroperacionflete','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data','class'=>'submit-prevent-form'))!!} 
			{{Form::token()}}
			<input type="text" name="id" value="{{$idoperacion}}" hidden="">
			<div class="Form-group">
				<label for="importefletextonelada">Flete x Tonelada</label>
				<input type="number" step=0.01 name="importefletextonelada" class="form-control {{$errors->has('importefletextonelada')?'is-invalid':''}}" placeholder="Precio del flete x tonelada..." value="{{old('importefletextonelada')}}">
				{!! $errors->first('importefletextonelada','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<br>
			<div class="Form-group">
				<button class="btn btn-primary submit-prevent-button" type="submit"><i class="spinner fa fa-spinner fa-spin"></i>Guardar</button>
				<button class="btn btn-danger" type="reset">Cancelar</button>
			</div>

			
			{!!Form::close()!!}
			
			<div class="Form-group">
				<br/>
				<a href="/operaciones"><button class="btn btn-success">Regresar</button></a>
			</div>

		</div>
	</div> 
@endsection