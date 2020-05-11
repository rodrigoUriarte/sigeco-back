@extends('reportes.layout')


@section('content')
<div class="invoice">
    <h2>REPORTE INSCRIPCIONES</h2>
    <hr>
    <h4>Filtros Aplicados</h4>
    @if ($filtro_comensal)
    <div style="margin-bottom: 5px; margin-left: 20px">Comensal: {{App\User::find($filtro_comensal)->name}}</div>
    @else
    <div style="margin-bottom: 5px; margin-left: 20px">Comensal: {{$filtro_comensal?? 'No aplicado'}}</div>
    @endif
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Inscripcion Desde: {{$filtro_fecha_inscripcion_desde?? 'No aplicado' }}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Inscripcion Hasta: {{$filtro_fecha_inscripcion_hasta?? 'No aplicado' }}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Menu Asignado: {{$filtro_menu?? 'No aplicado' }}</div>
    @foreach ($inscripciones as $menu => $inscripcionesGrouped)
    <hr>
    {{$descripcion=App\Models\Menu::find($menu)->descripcion}}
    <hr>    
    <table width="100%" border="1">
        <thead>
            <tr style="line-height: 14px; font-size: 14px; background: #467FD0">
                <th style="color:white">Usuario</th>
                <th style="color:white">Fecha Inscripcion</th>
                <th style="color:white">Banda Horaria</th>
            </tr>
        </thead>
        <tbody>
            @if (sizeof($inscripcionesGrouped)>0)
            @foreach ($inscripcionesGrouped as $inscripcion)
            <tr>
                <td>{{$inscripcion->user->name}} </td>
                <td>{{$inscripcion->fecha_inscripcion_formato}} </td>
                <td>{{$inscripcion->bandaHoraria->descripcion}} </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <div style="margin-bottom: 5px; margin-left: 20px">Cantidad Inscripciones ({{$descripcion}}): {{sizeof($inscripcionesGrouped)}}</div>
    @endforeach
</div>
@stop