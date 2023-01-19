@extends('layouts.admin')
@section('contenido')
	<div class="row">
		<div class="col-lg-6 col-lg-6 col-lg-6 col-xs-12">
			<h3>Ingreso de Datos de entrega al cliente</h3>

			@if(count($errors)>0)
			<div class="alert alert-danger" role="alert">
				<ul>
					
					@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
			@endif	
 

 			{!!Form::open(array('url'=>'operaciones/guardaroperacioncliente','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data','class'=>'submit-prevent-form'))!!} 
			{{Form::token()}}
			<input type="text" name="id" value="{{$idoperacion}}" hidden="">
			<div class="Form-group">
				<label for="cliente_id">Seleccione Cliente</label>
				{!!Form::select('cliente_id',$clientes,null,['class' => 'form-control','placeholder'=>'Seleccione una opcion','requerid' ])!!}
			</div>
			<div class="Form-group">
				<label for="precioventa">Precio Venta convenido</label>
				<input type="number" step=0.01 name="precioventa" class="form-control {{$errors->has('precioventa')?'is-invalid':''}}" placeholder="Precio de venta convenida con el cliente..." value="{{old('precioconvenido')}}">
				{!! $errors->first('precioventa','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="kiloscliente">Kilos Pesados por el cliente</label>
				<input type="number"  name="kiloscliente" class="form-control {{$errors->has('kiloscliente')?'is-invalid':''}}" placeholder="Kilos pesados por el cliente..." value="{{old('kiloscliente')}}">
				{!! $errors->first('kiloscliente','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="importepagadocliente">Dinero Pagado por el cliente</label>
				<input type="number" step=0.01 name="importepagadocliente" class="form-control {{$errors->has('importepagadocliente')?'is-invalid':''}}" placeholder="Dinero Pagado por el cliente..." value="{{old('importepagadocliente')}}">
				{!! $errors->first('importepagadocliente','<div class="invalid-feedback">:message</div>')!!}
				
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