@extends('layouts.admin')
@section('contenido')

@if(Session::has('Mensaje')){{
	
	Session::get('Mensaje')
}}
@endif
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado Roles <a href="roles/create"><button class="btn btn-success">Nuevo</button></a></h3>
	</div>

</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Nombre</th>
					<th>Operaciones</th>
				</thead>
               @foreach ($roles as $role)
				<tr>
					<td width="1px">{{ $role->name}}</td>
					
					<td >
					<form method="post" action="{{url('abms/roles/'.$role->id) }}">
							<a href="{{url('abms/roles/'.$role->id.'/edit')}}"><input type="button" value="Editar" class="btn btn-info">	</a>
							{{csrf_field()}}
							{{method_field('DELETE')}}
							<button type="submit" onclick="return confirm('Seguro que desea Borrar?');" class="btn btn-danger">Eliminar</button>
					</form>
						
					</td>
				</tr>
				
				@endforeach
			</table>
		</div>
		{{$roles->render()}}
	</div>
</div>

@endsection