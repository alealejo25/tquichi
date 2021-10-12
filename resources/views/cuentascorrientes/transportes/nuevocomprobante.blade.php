@extends('layouts.admin')
@section('contenido')
	<div class="row">
		<div class="col-lg-6 col-lg-6 col-lg-6 col-xs-12">

			@foreach ($transportes as $transporte)
			<h3>Ingreso de comprobante</h3>
			<h4>Transporte: {{$transporte->nombre}}</h4>
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
			{!!Form::open(['route' => ['guardarcomprobantet',$id],'method'=>'POST'])!!}
			<div class="Form-group">
				<table>
					<tr>

						<td>
							<label for="tipocomprobante"> Tipo de Comprobante</label>
							<select name="tipocomprobante" id="tipo" class="form-control">
										<option value="">Selecccione un Tipo Comprobante</option>
										<option value="FACTURA">FACTURA</option>
										<option value="REMITO">REMITO</option>
										<option value="NOTA DE CREDITO">NOTA DE CREDITO</option>
										<option value="NOTA DE DEBITO">NOTA DE DEBITO</option>
										<option value="RECIBO">RECIBO</option>
							</select>
						</td>
						<td>&nbsp;</td>
						<td>
							<label for="nrocomprobante">Numero de Comprobante</label>
							<input type="text" name="nrocomprobante" class="form-control {{$errors->has('nrocomprobante')?'is-invalid':''}}" value="{{old('nrocomprobante')}}">
							{!! $errors->first('nrocomprobante','<div class="invalid-feedback">:message</div>')!!}
						</td>
					</tr>
				</table>
			</div>


			<div class="Form-group">
				<label for="fechaemision">Fecha Emision</label>
				<input type="date" name="fechaemision" id="fechaemision" class="form-control {{$errors->has('fechaemision')?'is-invalid':''}}" placeholder="Fecha de emision..." value="{{old('fechaemision')}}">
				{!! $errors->first('fechaemision','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="fechavencimiento">Fecha Vencimiento</label>
				<input type="date" name="fechavencimiento" id="fechavencimiento" class="form-control {{$errors->has('fechavencimiento')?'is-invalid':''}}" placeholder="Fecha de vencimiento..." value="{{old('fechavencimiento')}}">
				{!! $errors->first('fechavencimiento','<div class="invalid-feedback">:message</div>')!!}
			</div>
			<div class="Form-group">
				<label for="importe">Importe</label>
				<input type="number" step=0.01 name="importe" class="form-control {{$errors->has('importe')?'is-invalid':''}}" placeholder="Importe..." value="{{old('importe')}}">
				{!! $errors->first('importe','<div class="invalid-feedback">:message</div>')!!}
			</div>
			

			<div class="Form-group">
				<label for="observacion">Observacion</label>
				<input type="text" name="observacion" class="form-control {{$errors->has('observacion')?'is-invalid':''}}" value="{{old('observacion')}}"> 
				{!! $errors->first('observacion','<div class="invalid-feedback">:message</div>')!!}
			</div>

			<br>
			<div class="Form-group">
				<button class="btn btn-primary" type="submit">Guardar</button>
				<button class="btn btn-danger" type="reset">Cancelar</button>
			</div>

			
			{!!Form::close()!!}
			
			<div class="Form-group">
				<br/>
				<a href="/cuentascorrientes/proveedores"><button class="btn btn-success">Regresar</button></a>
			</div>

		</div>
	</div> 
@endsection