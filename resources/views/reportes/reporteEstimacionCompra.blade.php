@extends('reportes.layout')

@section('content')

<div class="invoice">
    <h2>REPORTE ESTIMACION COMPRA</h2>
    <hr>
    <h4>Filtros Aplicados</h4>
    <div style="margin-bottom: 5px; margin-left: 20px">Insumo: {{$filtro_insumo?? 'No aplicado'}}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Vencimiento Desde:
        {{$filtro_fecha_vencimiento_desde?? 'No aplicado' }}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Vencimiento Hasta:
        {{$filtro_fecha_vencimiento_hasta?? 'No aplicado' }}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Incluir Lotes Vacios: {{$filtro_lotes_vacios?? 'No aplicado' }}
    </div>
    <hr>

    <table style="page-break-inside:auto"  width="100%" border="1">
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
            @if (sizeof($menus)>0)
            @foreach ($menus as $menu => $platos)
            <tr>
                @php
                $insumosCount=0;
                foreach ($platos as $plato => $insumos) {
                foreach ($insumos as $insumo => $value) {
                $insumosCount += 1;
                }
                }
                @endphp
                <td rowspan="{{$insumosCount}}">
                    {{$menu}}
                </td>

                @foreach ($platos as $plato => $insumos)
                @if ($plato === array_key_first($platos))
                <td rowspan="{{count($insumos)}}">
                    {{$plato}}
                </td>
                @else
            <tr>
                <td rowspan="{{count($insumos)}}">
                    {{$plato}}
                </td>
                @endif

                @foreach ($insumos as $insumo => $array)
                @php
                $value = reset($array);
                @endphp
                @if ($insumo === array_key_first($insumos))
                <td>
                    {{$insumo}}
                </td>
                <td>
                    @if ($value['estado'] == true)
                    REMANENTE:
                    @endif
                    @if ($value['estado'] == false)
                    FALTANTE:
                    @endif
                </td>
                <td align="right">{{$value['cantidad']}}</td>
                @else
            <tr>
                <td>
                    {{$insumo}}
                </td>
                <td>
                    @if ($value['estado'] == true)
                    REMANENTE:
                    @endif
                    @if ($value['estado'] == false)
                    FALTANTE:
                    @endif
                </td>
                <td align="right">{{$value['cantidad']}}</td>
                @endif
            </tr>

            @endforeach
            </tr>

            @endforeach
            </tr>
            @endforeach

            @endif
        </tbody>
    </table>
</div>
@stop


@section('cantidad')
{{sizeof($menus)}}
@stop