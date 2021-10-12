@extends('layouts.admin')
@section('contenido')
	<div class="row">
		<div class="col-lg-6 col-lg-6 col-lg-6 col-xs-12">
			<h3>Ingreso de Datos en Proveedor</h3>

			@if(count($errors)>0)
			<div class="alert alert-danger" role="alert">
				<ul>
					
					@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
			@endif	
			@foreach ($proveedor as $datos)
				
				<h4><label>Proveedor:</label>  	{{ $datos->nombre}}  / <label>Saldo:</label> {{ $datos->saldo}}</h4>
				
			@endforeach

 			{!!Form::open(array('url'=>'operaciones/guardaroperacionproveedor','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data','class'=>'submit-prevent-form'))!!} 
			{{Form::token()}}
			<input type="text" name="id" value="{{$idoperacion}}" hidden="">
			<div class="Form-group">
				<label for="precioconvenido">Precio Convenido</label>
				<input type="number" step=0.01 name="precioconvenido" class="form-control {{$errors->has('precioconvenido')?'is-invalid':''}}" placeholder="Precio convenido con el Proveedor..." value="{{old('precioconvenido')}}">
				{!! $errors->first('precioconvenido','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="kilosprovistos">Kilos Provistos</label>
				<input type="number"  name="kilosprovistos" class="form-control {{$errors->has('kilosprovistos')?'is-invalid':''}}" placeholder="Kilos provistos..." value="{{old('kilosprovistos')}}">
				{!! $errors->first('kilosprovistos','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="importepagadoproveedor">Dinero Pagado al Proveedor</label>
				<input type="number" step=0.01 name="importepagadoproveedor" class="form-control {{$errors->has('importepagadoproveedor')?'is-invalid':''}}" placeholder="Dinero Pagado al Proveedor..." value="{{old('importepagadoproveedor')}}">
				{!! $errors->first('importepagadoproveedor','<div class="invalid-feedback">:message</div>')!!}
				<p><small>INGRESE 0(cero) SI NO PAGA AL PROVEEDOR</small></p>
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