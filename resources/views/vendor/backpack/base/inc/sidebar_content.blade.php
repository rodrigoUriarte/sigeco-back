<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@if(backpack_user()->hasRole('comensal'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menuAsignado') }}'><i class='nav-icon fa fa-question'></i> MenuAsignados</a></li>
@endif

{{-- @if(backpack_user()->hasRole('admin')) --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class=nav-item><a class=nav-link href="{{ backpack_url('elfinder') }}"><i class="nav-icon fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>

<!-- Users, Roles, Permissions -->
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> Authentication</a>
	<ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon fa fa-user"></i> <span>Users</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon fa fa-group"></i> <span>Roles</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon fa fa-key"></i> <span>Permissions</span></a></li>
	</ul>
</li>
{{-- @endif --}}

<li class='nav-item'><a class='nav-link' href="{{ backpack_url('unidadAcademica') }}"><i class='nav-icon fa fa-university'></i> UnidadesAcademicas</a></li>
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('comedor') }}"><i class='nav-icon fa fa-building'></i> Comedores</a></li>
<!-- <li class='nav-item'><a class='nav-link' href="{{ backpack_url('ingreso') }}"><i class='nav-icon fa fa-cube'></i> Ingresos</a></li> -->
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('persona') }}"><i class='nav-icon fa fa-user'></i> Personas</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menu') }}'><i class='nav-icon fa fa-question'></i> Menus</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menuAsignado') }}'><i class='nav-icon fa fa-question'></i> MenuAsignados</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('bandaHoraria') }}'><i class='nav-icon fa fa-question'></i> BandaHorarias</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('inscripcion') }}'><i class='nav-icon fa fa-question'></i> Inscripcions</a></li>