@extends('reportes.layout')


@section('content')
<div class="invoice">
    <h2>REPORTE INSCRIPCIONES</h2>
    <hr>
    <h4>Filtros Aplicados</h4>
    <div style="margin-bottom: 5px; margin-left: 20px">Usuario: {{$filtro_usuario?? 'No aplicado'}}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Inscripcion Desde: {{$filtro_fecha_inscripcion_desde?? 'No aplicado' }}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Inscripcion Hasta: {{$filtro_fecha_inscripcion_hasta?? 'No aplicado' }}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Menu Asignado: {{$filtro_menu?? 'No aplicado' }}</div>
    <hr>
    <table width="100%" border="1">
        <thead>
            <tr style="line-height: 14px; font-size: 14px; background: #467FD0">
                <th style="color:white">Usuario</th>
                <th style="color:white">Fecha Inscripcion</th>
                <th style="color:white">Banda Horaria</th>
                <th style="color:white">Menu Asignado</th>
            </tr>
        </thead>
        <tbody>
            @if (sizeof($inscripciones)>0)
            @foreach ($inscripciones as $inscripcion)
            <tr>
                <td>{{$inscripcion->user->name}} </td>
                <td>{{$inscripcion->fecha_inscripcion_formato}} </td>
                <td>{{$inscripcion->bandaHoraria->descripcion}} </td>
                <td>{{$inscripcion->menuAsignado->menu->descripcion}} </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
@stop


@section('cantidad')
{{sizeof($inscripciones)}}
@stop