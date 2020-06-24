@extends(backpack_view('blank'))

@php
$defaultBreadcrumbs = [
trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
$crud->entity_name_plural => url($crud->route),
trans('backpack::crud.add') => false,
];

// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
<section class="container-fluid">
	<h2>
		<span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
		<small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

		@if ($crud->hasAccess('list'))
		<small><a href="{{ url($crud->route) }}" class="hidden-print font-sm"><i
					class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i>
				{{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
		@endif
	</h2>
</section>
@endsection

@section('content')

<div class="row">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		<form method="post" action="{{ url($crud->route) }}" @if ($crud->hasUploadFields('create'))
			enctype="multipart/form-data"
			@endif
			>
			{!! csrf_field() !!}

			<div class="card">
				<div class="card-header">
					Remito
				</div>

				<div class="card-body">
					<input type="hidden" name="fecha" value="{{$fecha}}">
					<input type="hidden" name="comedor_id" value="{{$comedor}}">
					<div class="form-group {{ $errors->has('numero') ? 'text-danger' : false }}">
						<label for="numero">Numero de remito <span style="color: red">*</span></label>
						<input type="number" class="form-control  {{ $errors->has('numero') ? 'is-invalid' : false }}"
							id="numero" name="numero" placeholder="Ingrese el numero del remito" required
							value="{{old('numero')}}">
						{!! $errors->first('numero', ' <div class="invalid-feedback">:message</div>') !!}

					</div>
					<div class="form-group {{ $errors->has('proveedor_id') ? 'text-danger' : false }}">
						<label for="proveedor">Proveedor <span style="color: red">*</span></label>
						<select class="form-control {{ $errors->has('proveedor_id') ? 'is-invalid' : false }}" 
							name="proveedor_id" id="proveedor" required>
							<option></option>
							@if($proveedores)
							@foreach($proveedores as $proveedor)
							<option value="{{$proveedor->id}}"
								{{ $proveedor->id == old('proveedor_id') ? 'selected' : '' }}>
								{{$proveedor->nombre}}</option>
							@endforeach
							@endif
						</select>
						{!! $errors->first('proveedor_id', ' <div class="invalid-feedback">:message</div>') !!}
					</div>

				</div>
			</div>

			<div class="card">
				<div class="card-header">
					Insumos
				</div>

				<div class="card-body">
					<div class="table-responsive">
						<table class="table" id="insumos_table">
							<thead>
								<tr>
									<th>Insumo <span style="color: red">*</span></th>
									<th>Cantidad <span style="color: red">*</span></th>
									<th>Fecha Vencimiento <span style="color: red">*</span></th>
									<th>Accion</th>
								</tr>
							</thead>
							<tbody>
								{{-- AL MOMENTO DE CREAR SI LAS VALIDACIONES FALLAN SE VUELVEN A CARGAR LOS CAMPOS CON 
								LOS DATOS INGRESADOS POR EL USUARIO --}}
								@if (old('insumo'))
								@for( $i=0; $i < count(old('insumo')); $i++) <tr>
									<td>
										<select class="select2 form-control" name="insumo[]" required>
											<option></option>
											@if($insumos)
											@foreach($insumos as $insumo)
											<option value="{{$insumo->id}}"
												{{ $insumo->id == old('insumo.'.$i) ? 'selected' : '' }}>
												{{$insumo->descripcionUM}}</option>
											@endforeach
											@endif
										</select>
									</td>
									<td>
										<input type="number" step="0.01" name="cantidad[]" class="form-control" required
											placeholder="Ingrese la cantidad" value="{{old('cantidad.'.$i)}}">

									</td>
									<td>
										<input type="date" name="fecha_vencimiento[]" class="form-control" required
											value="{{old('fecha_vencimiento.'.$i)}}">
									</td>
									<td><button class="delete_row pull-right btn btn-danger"><i
												class="la la-remove"></i></button></td>
									</tr>
									@endfor
									@endif
							</tbody>
						</table>
					</div>

					<br>
					<div class="row">
						<div class="col-md-12">
							<button id="add_row" class="pull-left btn btn-default ">+ Agregar Insumo</button>
						</div>
					</div>
				</div>
			</div>

			@include('crud::inc.form_save_buttons')
		</form>
	</div>
</div>

@endsection

@section('after_scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/i18n/es.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

    $.fn.select2.defaults.set('language', 'es');

    function select2Insumos(selectElementObj) {
        selectElementObj.select2({
            placeholder: 'Seleccione un insumo',
            width: '100%',
            allowClear: true,
            //minimumInputLength: 3,
        });
    };

    $(".select2").each(function() {
        select2Insumos($(this));
    });

    $("#add_row").click(function(e) {
        e.preventDefault();
        //new row
        $("#insumos_table").append(
            '<tr>\
                <td>\
                    <select class="select2 form-control" name="insumo[]" required>\
                        <option></option>\
                        @if($insumos)\
                        @foreach($insumos as $insumo)\
                        <option value="{{$insumo->id}}">{{$insumo->descripcionUM}}</option>\
                        @endforeach\
                        @endif\
                    </select>\
                </td>\
                <td>\
                    <input type="number" step="0.01" name="cantidad[]" class="form-control" required placeholder="Ingrese la cantidad">\
                </td>\
                <td>\
                    <input type="date" name="fecha_vencimiento[]" class="form-control" required>\
                </td>\
                <td><button class="delete_row pull-right btn btn-danger"><i class="la la-remove"></i></button></td>\
            </tr>'
        );
        var newSelect=$("#insumos_table").find(".select2").last();
        select2Insumos(newSelect);
    });

    $("#insumos_table").on("click", ".delete_row", function() {
        $(this).closest("tr").remove();
    });

    $("#proveedor").select2({
        placeholder: 'Seleccione un proveedor',
        width: '100%',
        allowClear: true,
    });
});

</script>
@endsection