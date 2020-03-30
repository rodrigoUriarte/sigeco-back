<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->

{{-- COMENSAL --}}

<head>
	<script src="https://kit.fontawesome.com/2fad2a3c47.js" crossorigin="anonymous"></script>
</head>

@if(backpack_user()->hasRole('comensal'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i>
		{{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-layer-group"></i>
		GESTION ASISTENCIAS</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menuAsignado') }}'><i
					class='nav-icon fas fa-calendar-alt'></i> Menus Asignados</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('diapreferencia') }}'><i
					class='nav-icon fas fa-calendar-day'></i> Dias Preferencia</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('inscripcion') }}'><i
					class='nav-icon fas fa-calendar-plus'></i> Inscripciones</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('asistencia') }}'><i
					class="nav-icon fas fa-calendar-check"></i> Asistencias</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('sancion') }}'><i
					class='nav-icon fas fa-ban'></i> Sanciones</a></li>

	</ul>
</li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('ayuda') }}'><i
			class='nav-icon fas fa-question-circle'></i> AYUDA</a></li>
@endif

{{-- OPERATIVO --}}
@if(backpack_user()->hasRole('operativo'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i>
		{{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i
			class="nav-icon fas fa-layer-group"></i>GESTION USUARIOS</a>
	<ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon fas fa-user"></i>
				<span>Users</span></a></li>
		<li class='nav-item'><a class='nav-link' href="{{ backpack_url('persona') }}"><i
					class='nav-icon fas fa-male'></i> Personas</a></li>
	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-layer-group"></i>
		GESTION COMEDORES</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('parametro') }}'><i
					class='nav-icon fas fa-hourglass-half'></i> Parametros</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('diaservicio') }}'><i
					class='nav-icon fas fa-calendar-week'></i> Dias Servicio</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('bandaHoraria') }}'><i
					class='nav-icon fas fa-clock'></i> Bandas Horarias</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('regla') }}'><i
					class='nav-icon fas fa-balance-scale'></i> Reglas</a></li>
	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-layer-group"></i>
		GESTION ASISTENCIAS</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menuAsignado') }}'><i
					class='nav-icon fas fa-calendar-alt'></i> Menus Asignados</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('diapreferencia') }}'><i
					class='nav-icon fas fa-calendar-day'></i> Dias Preferencia</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('inscripcion') }}'><i
					class='nav-icon fas fa-calendar-plus'></i> Inscripciones</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('asistencia') }}'><i
					class="nav-icon fas fa-calendar-check"></i> Asistencias</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('sancion') }}'><i
					class='nav-icon fas fa-ban'></i> Sanciones</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('justificacion') }}'><i
					class='nav-icon fas fa-balance-scale-right'></i> Justificaciones</a></li>
		<li class='nav-item'><a class='nav-link' href='{{backpack_url('estadisticas')}}'><i
					class='nav-icon fas fa-chart-bar'></i> Estadisticas</a></li>

	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-layer-group"></i>
		GESTION
		INSUMOS/PLATOS</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menu') }}'><i
					class='nav-icon fas fa-utensils'></i> Menus</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('plato') }}'><i
					class='nav-icon fas fa-drumstick-bite'></i> Platos</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('insumo') }}'><i
					class='nav-icon fas fa-carrot'></i> Insumos</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('insumoPlato') }}'><i
					class='nav-icon fas fa-fish'></i> Insumo Plato</a></li>
	</ul>

</li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-layer-group"></i>
		GESTION
		INGRESOS/EGRESOS</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lote') }}'><i class='nav-icon fas fa-boxes'></i>
				Lotes</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('ingresoInsumo') }}'><i
					class='nav-icon fas fa-truck-loading'></i> Ingresos Insumos</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('platoAsignado') }}'><i
					class='nav-icon fas fa-calendar-minus'></i> Platos Asignados</a></li>
		<li class='nav-item'><a class='nav-link' href='{{backpack_url('calculoEstimacionCompra')}}'><i
					class='nav-icon fas fa-calculator'></i> Estimacion Compra</a></li>
	</ul>
</li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('ayuda') }}'><i
			class='nav-icon fas fa-question-circle'></i> AYUDA</a></li>

@endif


{{-- SUPERADMIN --}}
@if(backpack_user()->hasRole('superAdmin'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i>
		{{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-layer-group"></i>
		GESTION USUARIOS</a>
	<ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon fas fa-user"></i>
				<span>Users</span></a></li>
		<li class='nav-item'><a class='nav-link' href="{{ backpack_url('persona') }}"><i
					class='nav-icon fas fa-male'></i> Personas</a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon fa fa-group"></i>
				<span>Roles</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i
					class="nav-icon fa fa-key"></i> <span>Permissions</span></a>
		</li>
	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-layer-group"></i>
		GESTION
		UA/COMEDORES</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href="{{ backpack_url('unidadAcademica') }}"><i
					class='nav-icon fa fa-university'></i> Unidades Academicas</a></li>
		<li class='nav-item'><a class='nav-link' href="{{ backpack_url('comedor') }}"><i
					class='nav-icon fa fa-building'></i> Comedores</a></li>

	</ul>
</li>
@endif

{{-- AUDITOR --}}
@if(backpack_user()->hasRole('auditor'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i>
		{{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-layer-group"></i>
		AUDITORIA</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('auditoria') }}'><i
					class='nav-icon fas fa-search'></i> Registros Auditorias</a></li>
	</ul>
</li>

@endif

{{-- SECRETARIO --}}
@if(backpack_user()->hasRole('secretario'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i>
		{{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-layer-group"></i>
		ESTADISTICAS</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{backpack_url('estadisticas')}}'><i
					class='nav-icon fas fa-chart-bar'></i> Estadisticas</a></li>
	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a style="font-size:10px" class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-layer-group"></i>
		REPORTES</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('inscripcion') }}'><i
					class='nav-icon fas fa-calendar-plus'></i> Inscripciones</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lote') }}'><i class='nav-icon fas fa-boxes'></i>
				Lotes</a></li>
	</ul>
</li>

@endif



{{-- TODO POR LAS DUDAS --}}
{{-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i
	class="fa fa-dashboard nav-icon"></i>
{{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> AUTENTICACION</a>
	<ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon fa fa-user"></i>
				<span>Users</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon fa fa-group"></i>
				<span>Roles</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i
					class="nav-icon fa fa-key"></i> <span>Permissions</span></a></li>
		<li class='nav-item'><a class='nav-link' href="{{ backpack_url('persona') }}"><i
					class='nav-icon fa fa-user'></i> Personas</a></li>
	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> COMEDORES</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href="{{ backpack_url('unidadAcademica') }}"><i
					class='nav-icon fa fa-university'></i> Unidades Academicas</a></li>
		<li class='nav-item'><a class='nav-link' href="{{ backpack_url('comedor') }}"><i
					class='nav-icon fa fa-building'></i> Comedores</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('bandaHoraria') }}'><i
					class='nav-icon fa fa-question'></i> Bandas Horarias</a></li>
	</ul>
</li>


<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> INSCRIPCIONES</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menuAsignado') }}'><i
					class='nav-icon fa fa-question'></i> Menus Asignados</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('inscripcion') }}'><i
					class='nav-icon fa fa-question'></i> Inscripciones</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('asistencia') }}'><i
					class='nav-icon fa fa-question'></i> Asistencias</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('regla') }}'><i
					class='nav-icon fa fa-question'></i> Reglas</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('sancion') }}'><i
					class='nav-icon fa fa-question'></i> Sanciones</a></li>
	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> ALIMENTOS</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menu') }}'><i
					class='nav-icon fa fa-question'></i> Menus</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('plato') }}'><i
					class='nav-icon fa fa-question'></i> Platos</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('insumo') }}'><i
					class='nav-icon fa fa-question'></i> Insumos</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('insumoPlato') }}'><i
					class='nav-icon fa fa-question'></i> Insumo Plato</a></li>
	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> INSUMOS</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lote') }}'><i
					class='nav-icon fa fa-question'></i> Lotes</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('ingresoInsumo') }}'><i
					class='nav-icon fa fa-question'></i> Ingresos Insumos</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('platoAsignado') }}'><i
					class='nav-icon fa fa-question'></i> Platos Asignados</a></li>
	</ul>
</li> --}}