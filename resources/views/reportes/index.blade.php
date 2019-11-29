@extends('admin_panel.index')


@section('content')



<br>

<div class="container">

	<div class="row">
		<div class="col-sm-12">
			<div class="card text-left">

				<div class="card-header">

					<div class="card-tools">
						<button type="button" class="btn btn-tool" data-card-widget="collapse"><i
								class="fas fa-minus"></i></button>
					</div>
					<h3>Filtro de Proveedores</h3>
				</div>


				<div class="card-body">
					<form action="{{route('pdf.proveedor')}}" method="GET" enctype="multipart/form-data">
						@csrf
						<div align="right">
							<button type="submit" class="btn  btn-success  btn-flat btn-sm">Reporte Proveedor</button>
						</div>
						<hr>
						<div class="row">

							<div class="form-group col-md-3">
								<label>Nombre : </label>
								<input class="form-control" type="text" name="filtro_nombre" id="filtro_nombre"
									data-placeholder="Ingrese un nombre a filtrar" style="width: 100%;">
							</div>
							<div class="form-group col-md-3">
								<label>Numero de Documento : </label>
								<input class="form-control" type="text" name="filtro_documento" id="filtro_documento"
									data-placeholder="Ingrese un nombre a filtrar" style="width: 100%;">
							</div>
							<div class="form-group col-md-3">
								<label>Email : </label>
								<input class="form-control" type="text" name="filtro_email" id="filtro_email"
									data-placeholder="Ingrese un nombre a filtrar" style="width: 100%;">
							</div>

						</div>
					</form>
				</div>
				<div class="card-footer text-muted">
					<div class="text-center">
						<button type="button" name="filtrar" id="filtrar"
							class="btn btn-success btn-sm">Filtrar</button>
						<button type="button" name="reiniciar" id="reiniciar" class="btn btn-info btn-sm">Reiniciar
							Tabla</button>
					</div>

				</div>
			</div>

			<div class="card text-left">


				<div class="card-header">
					<h3>Lista de Proveedores</h3>
				</div>
				<div class="card-body">

					<div align="left">
						<button type="button" name="create_record" id="create_record"
							class="btn btn-success btn-sm">Crear Nuevo Proveedor</button>

					</div>

					<hr>
					<div class="table-responsive ">
						<table class='table table-bordered table-striped table-hover datatable' id='data-table'>
							<thead style="background-color:white ; color:black;">
								<tr class="justify-content-start">
									<th>ID</th>
									<th>Nombre</th>
									<th>Email</th>
									<th>Razon Social</th>
									<th>Documento </th>
									<th>Direccion </th>
									<th>&nbsp; </th>


								</tr>
							</thead>
							<tbody style="background-color:white ; color:black;">
								@if (sizeof($proveedores)>0)

								@foreach ($proveedores as $proveedor)
								<tr>

									<td>{{$proveedor->id}} </td>
									<td>{{$proveedor->nombre}} </td>
									<td>{{$proveedor->email}} </td>
									<td>{{$proveedor->razonSocial}} </td>


									<td>{{$proveedor->documento->nombre .' - '.$proveedor->numeroDocumento}} </td>

									@if ($proveedor->direccion!=null)
									<td>

										{{ $proveedor->direccion->calle->nombre . ' - '.$proveedor->direccion->numero . ' - '.
									$proveedor->direccion->localidad->nombre . ' - '.$proveedor->direccion->provincia->nombre . ' - '
									.$proveedor->direccion->pais->nombre }}

									</td>
									@else
									<td>Sin Direccion </td>
									@endif

									<td>
										<div class="row">

											<button type="button" name="edit" id="{{$proveedor->id}}"
												class="edit btn btn-outline-primary btn-sm">Editar</button>
											&nbsp;&nbsp;
											<button type="button" name="delete" id="{{$proveedor->id}}"
												class="delete btn btn-outline-danger btn-sm">Eliminar</button>

										</div>
									</td>


								</tr>
								@endforeach
								@endif
							</tbody>

							<tfoot style="background-color:#ccc; color:white;">
								<tr>
									<th>ID</th>
									<th>Nombre</th>
									<th>Email</th>
									<th>Razon Social</th>
									<th>Documento </th>
									<th>Direccion </th>
									<th>&nbsp; </th>

								</tr>
							</tfoot>

						</table>
					</div>
				</div>
				<div class="card-footer text-muted">
					{{-- 2 days ago --}}
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


@section('htmlFinal')
@include('proveedor.modal')
@endsection