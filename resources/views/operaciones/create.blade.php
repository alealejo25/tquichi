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
<hr style="color: black; background-color: black; width:75%;" />
			<div class="Form-group">
				<label for="precioconvenido">Precio Convenido</label>
				<input type="number" step=0.01 name="precioconvenido" class="form-control {{$errors->has('precioconvenido')?'is-invalid':''}}" placeholder="Precio convenido con el Proveedor..." value="{{old('precioconvenido')}}">
				{!! $errors->first('precioconvenido','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group" >
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
			<hr style="color: black; background-color: black; width:75%;" />

			<div class="form-group">
 					<label for="cliente_id">Seleccione Cliente</label>
					<select name="cliente_id" id="cliente_id" class="form-control">
					<option value="">Selecccione un cliente</option>
						@foreach ($clientes as $cliente) 
					<option value="{{ $cliente->id }}">{{$cliente->nombre}}</option>
						@endforeach
					</select>
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



			<div class="form-group">
 					<label for="cliente_id1">Seleccione Cliente</label>
					<select name="cliente_id1" id="cliente_id1" class="form-control">
					<option value="">Selecccione un cliente</option>
						@foreach ($clientes as $cliente) 
					<option value="{{ $cliente->id }}">{{$cliente->nombre}}</option>
						@endforeach
					</select>
 				</div>
			<div class="Form-group">
				<label for="precioventa1">Precio Venta convenido</label>
				<input type="number" step=0.01 name="precioventa1" class="form-control {{$errors->has('precioventa1')?'is-invalid':''}}" placeholder="Precio de venta convenida con el cliente..." value="{{old('precioventa1')}}">
				{!! $errors->first('precioventa1','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="kiloscliente1">Kilos Pesados por el cliente</label>
				<input type="number"  name="kiloscliente1" class="form-control {{$errors->has('kiloscliente1')?'is-invalid':''}}" placeholder="Kilos pesados por el cliente..." value="{{old('kiloscliente1')}}">
				{!! $errors->first('kiloscliente1','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="importepagadocliente1">Dinero Pagado por el cliente</label>
				<input type="number" step=0.01 name="importepagadocliente1" class="form-control {{$errors->has('importepagadocliente1')?'is-invalid':''}}" placeholder="Dinero Pagado por el cliente..." value="{{old('importepagadocliente1')}}">
				{!! $errors->first('importepagadocliente1','<div class="invalid-feedback">:message</div>')!!}
			</div>




			<hr style="color: black; background-color: black; width:75%;" />
			<div class="Form-group">
				<label for="importefletextonelada">Flete x Tonelada</label>
				<input type="number" step=0.01 name="importefletextonelada" class="form-control {{$errors->has('importefletextonelada')?'is-invalid':''}}" placeholder="Precio del flete x tonelada..." value="{{old('importefletextonelada')}}">
				{!! $errors->first('importefletextonelada','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<hr style="color: black; background-color: black; width:75%;" />
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