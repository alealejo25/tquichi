@extends('layouts.admin')
@section('contenido')
	<div class="row">
		<div class="col-lg-6 col-lg-6 col-lg-6 col-xs-12">

			@foreach ($clientes as $cliente)
			<h3>Ingreso de comprobante</h3>
			<h4>Cliente: {{$cliente->nombre}}</h4>
			@endforeach
			@if(count($errors)>0)
			<div class="alert alert-danger" role="alert">
				<ul>
					
					@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
			@endif	
 			<!-- {!!Form::open(array('url'=>'fletes','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}  -->
			<!-- {!!Form::model(['method'=>'POST','route'=>['camiones.store']])!!}-->
			{!!Form::open(['route' => ['guardaropc',$id],'method'=>'POST'])!!}
			<div class="Form-group">
				<label for="fecha">Fecha</label>
				<input type="date" name="fecha" id="fecha" class="form-control {{$errors->has('fecha')?'is-invalid':''}}" placeholder="Fecha ..." value="{{old('fecha')}}">
				{!! $errors->first('fecha','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="nrocomprobante">Numero de Comprobante</label>
				<input type="text" name="nrocomprobante" class="form-control {{$errors->has('nrocomprobante')?'is-invalid':''}}" value="{{old('nrocomprobante')}}">
				{!! $errors->first('nrocomprobante','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="montoneto">Monto Neto</label>
				<input type="number" step=0.01 name="montoneto" class="form-control {{$errors->has('montoneto')?'is-invalid':''}}" placeholder="Monto Neto..." value="{{old('montoneto')}}">
				{!! $errors->first('montoneto','<div class="invalid-feedback">:message</div>')!!}
			</div>

			<div class="Form-group">
				<label for="descripcion">Descripcion</label>
				<input type="text" name="descripcion" class="form-control {{$errors->has('descripcion')?'is-invalid':''}}" value="{{old('descripcion')}}"> 
				{!! $errors->first('descripcion','<div class="invalid-feedback">:message</div>')!!}
			</div>


		
			<br>
			<div class="Form-group">
				<button class="btn btn-primary" type="submit">Guardar</button>
				<button class="btn btn-danger" type="reset">Cancelar</button>
			</div>

			
			{!!Form::close()!!}
			
			<div class="Form-group">
				<br/>
				<a href="/cuentascorrientes/clientes"><button class="btn btn-success">Regresar</button></a>
			</div>

		</div>
	</div> 
@endsection