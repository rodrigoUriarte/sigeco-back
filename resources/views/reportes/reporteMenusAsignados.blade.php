@extends('reportes.layout')


@section('content')
<div class="invoice">
    <h2>REPORTE MENUS ASIGNADOS</h2>
    <hr>
    <h4>Filtros Aplicados</h4>
    @if ($filtro_comensal)
    <div style="margin-bottom: 5px; margin-left: 20px">Comensal: {{App\User::find($filtro_comensal)->name}}</div>
    @else
    <div style="margin-bottom: 5px; margin-left: 20px">Comensal: {{$filtro_comensal?? 'No aplicado'}}</div>
    @endif
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Desde: {{$filtro_fecha_desde?? 'No aplicado' }}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Hasta: {{$filtro_fecha_hasta?? 'No aplicado' }}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Menu: {{$filtro_menu?? 'No aplicado' }}</div>
    @foreach ($menusAsignados as $menu => $menusAsignadosGrouped)
    <hr>
    {{$descripcion=App\Models\Menu::find($menu)->descripcion}}
    <hr>
    <table width="100%" border="1">
        <thead>
            <tr style="line-height: 14px; font-size: 14px; background: #467FD0">
                <th style="color:white">Comensal</th>
                <th style="color:white">Fecha Inicio</th>
                <th style="color:white">Fecha Fin</th>
            </tr>
        </thead>
        <tbody>
            @if (sizeof($menusAsignadosGrouped)>0)
            @foreach ($menusAsignadosGrouped as $menuAsignado)
            <tr>
                <td>{{$menuAsignado->user->name}} </td>
                <td>{{$menuAsignado->fecha_inicio}} </td>
                <td>{{$menuAsignado->fecha_fin}} </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <div style="margin-bottom: 5px; margin-left: 20px">Cantidad Menus Asignados ({{$descripcion}}): {{sizeof($menusAsignadosGrouped)}}</div>
    @endforeach
</div>
@stop