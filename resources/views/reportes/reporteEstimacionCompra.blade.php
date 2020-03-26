@extends('reportes.layout')


@section('content')

<div class="invoice">
    <h2>REPORTE ESTIMACION COMPRA</h2>
    <hr>
    <h4>Filtros Aplicados</h4>
    <div style="margin-bottom: 5px; margin-left: 20px">Insumo: {{$filtro_insumo?? 'No aplicado'}}.</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Vencimiento Desde:
        {{$filtro_fecha_vencimiento_desde?? 'No aplicado' }}.</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Vencimiento Hasta:
        {{$filtro_fecha_vencimiento_hasta?? 'No aplicado' }}.</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Incluir Lotes Vacios: {{$filtro_lotes_vacios?? 'No aplicado' }}.
    </div>
    <hr>
    <table width="100%" border="1">
        <thead>
            <tr style="line-height: 14px; font-size: 14px; background: lightgrey">
                <th>Menu</th>
                <th>Plato</th>
                <th>Insumo</th>
                <th>Estado</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @if (sizeof($mpifc)>0)
            @foreach ($mpifc as $value)
            <tr>
            <td>{{$value['menu']}}</td>
            <td>{{$value['plato']}}</td>
            <td>{{$value['insumo']}}</td>
            <td>@if($value['estado'] == false)
                FALTANTE
                @elseif ($value['estado'] == TRUE)
                REMANENTE
                @endif
            </td>
            <td>{{$value['cantidad']}}</td>

            </tr>

            @endforeach
            @endif
        </tbody>
    </table>
</div>
@stop


@section('cantidad')
{{sizeof($mpifc)}}
@stop