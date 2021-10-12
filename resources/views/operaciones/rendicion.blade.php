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

			@foreach ($operaciones as $datos)
				

				
			@endforeach
			@foreach ($operaciones as $datos)
				
				<p><label>Proveedor:</label>{{ $datos->proveedor->nombre}}/ <label>Importe Pagado:</label> {{ $datos->importepagadoproveedor}} / <label>Importe Convenido:</label> {{ $datos->importeconvenido}}
				<hr style="color: #0056b2;" /></p>
				<p><label>Cliente:</label>  {{ $datos->cliente->nombre}}/ <label>Importe Pagado:</label> {{ $datos->importepagadocliente}} / <label>Precio Convenido:</label> {{ $datos->importeventa}} </p>
				<hr style="color: #0056b2;" />				
				<p><label>Transporte:</label>  	{{ $datos->transporte->nombre}}  / <label>Flete x Tn:</label> {{ $datos->importefletextonelada}} / <label>Importe Flete:</label> {{ $datos->totalimporteflete}} / <label>Saldo de la operacion:</label> {{ $datos->saldofleteoperacion}}</p>
				
			@endforeach 

 			{!!Form::open(array('url'=>'operaciones/guardarrendicionflete','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data','class'=>'submit-prevent-form'))!!} 
			{{Form::token()}}
			<input type="text" name="id" value="{{$idoperacion}}" hidden="">
			<div class="Form-group">
				<label for="rendicionflete">Rendicion del Transporte</label>
				<input type="number" step=0.01 name="rendicionflete" class="form-control {{$errors->has('rendicionflete')?'is-invalid':''}}" placeholder="Rendicion del Transporte..." value="{{old('rendicionflete')}}">
				{!! $errors->first('rendicionflete','<div class="invalid-feedback">:message</div>')!!}
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