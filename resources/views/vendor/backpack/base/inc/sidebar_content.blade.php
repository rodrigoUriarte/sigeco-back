<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->

{{-- COMENSAL --}}
@if(backpack_user()->hasRole('comensal'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i>
		{{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> INSCRIPCIONES</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menuAsignado') }}'><i
					class='nav-icon fa fa-question'></i> Menus Asignados</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('inscripcion') }}'><i
					class='nav-icon fa fa-question'></i> Inscripciones</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('asistencia') }}'><i
					class='nav-icon fa fa-question'></i> Asistencias</a></li>
		<li class='nav-item'><a class='nav-link' href='{{ backpack_url('sancion') }}'><i
					class='nav-icon fa fa-question'></i> Sanciones</a></li>

	</ul>
</li>
@endif

{{-- ADMIN --}}
@if(backpack_user()->hasRole('admin'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i>
		{{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> AUTENTICACION</a>
	<ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon fa fa-user"></i>
				<span>Users</span></a></li>
		<li class='nav-item'><a class='nav-link' href="{{ backpack_url('persona') }}"><i
					class='nav-icon fa fa-user'></i> Personas</a></li>
	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> COMEDORES</a>
	<ul class="nav-dropdown-items">
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
</li>

@endif


{{-- SUPERADMIN --}}
@if(backpack_user()->hasRole('superAdmin'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i>
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
	</ul>
</li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> COMEDORES</a>
	<ul class="nav-dropdown-items">
		<li class='nav-item'><a class='nav-link' href="{{ backpack_url('unidadAcademica') }}"><i
					class='nav-icon fa fa-university'></i> Unidades Academicas</a></li>
		<li class='nav-item'><a class='nav-link' href="{{ backpack_url('comedor') }}"><i
					class='nav-icon fa fa-building'></i> Comedores</a></li>
	</ul>
</li>
@endif




{{-- TODO POR LAS DUDAS --}}
{{-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i>
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