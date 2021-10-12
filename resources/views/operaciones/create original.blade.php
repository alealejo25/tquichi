@extends('layouts.admin')
@section('contenido')
	<div class="row">
		<div class="col-lg-6 col-lg-6 col-lg-6 col-xs-12">
			<h3>Iniciar Operacion</h3>

			@if(count($errors)>0)
			<div class="alert alert-danger" role="alert">
				<ul>
					
					@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
			@endif	
 			{!!Form::open(array('url'=>'operaciones','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data','class'=>'submit-prevent-form'))!!} 
			{{Form::token()}}
			<div class="Form-group">
				<label for="transporte_id">Seleccione Transporte</label>
				{!!Form::select('transporte_id',$transportes,null,['class' => 'form-control','placeholder'=>'Seleccione una opcion','requerid' ])!!}
			</div>
			<div class="Form-group">
				<label for="proveedor_id">Seleccione Proveedor</label>
				{!!Form::select('proveedor_id',$proveedores,null,['class' => 'form-control','placeholder'=>'Seleccione una opcion','requerid' ])!!}
			</div>
			<div class="Form-group">
				<label for="dineroentregado">Dinero Entregado al Chofer</label>
				<input type="number" step=0.01 name="dineroentregado" class="form-control {{$errors->has('dineroentregado')?'is-invalid':''}}" placeholder="Dinero Entregado..." value="{{old('dineroentregado')}}">
				{!! $errors->first('dineroentregado','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="descripcion">Descripcion de la operacion</label>
				<input type="text"  name="descripcion" class="form-control {{$errors->has('descripcion')?'is-invalid':''}}" placeholder="Descripcion..." value="{{old('descripcion')}}">
				{!! $errors->first('descripcion','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<!-- <div class="Form-group">
				<label for="precioconvenido">Precio Convenido</label>
				<input type="number" step=0.01 name="precioconvenido" class="form-control {{$errors->has('precioconvenido')?'is-invalid':''}}" placeholder="Precio convenido..." value="{{old('precioconvenido')}}">
				{!! $errors->first('precioconvenido','<div class="invalid-feedback">:message</div>')!!}
			</div> -->
			
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