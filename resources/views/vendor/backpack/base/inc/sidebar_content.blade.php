<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class=nav-item><a class=nav-link href="{{ backpack_url('elfinder') }}"><i class="nav-icon fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('comedor') }}'><i class='nav-icon fa fa-building'></i> Comedores</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('comensal') }}'><i class='nav-icon fa fa-user'></i> Comensales</a></li>
<!-- Users, Roles, Permissions -->
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> Authentication</a>
	<ul class="nav-dropdown-items">
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon fa fa-user"></i> <span>Users</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon fa fa-group"></i> <span>Roles</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon fa fa-key"></i> <span>Permissions</span></a></li>
	</ul>
</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('ingreso') }}'><i class='nav-icon fa fa-question'></i> Ingresos</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('persona') }}'><i class='nav-icon fa fa-question'></i> Personas</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('unidad_academica') }}'><i class='nav-icon fa fa-question'></i> Unidad_academicas</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('operativo') }}'><i class='nav-icon fa fa-question'></i> Operativos</a></li>