@extends('reportes.layout')

@section('content')

<div class="invoice">
    <h2>REPORTE ESTIMACION COMPRA</h2>
    <hr>
    <h4>FILTROS APLICADOS</h4>
    @if ($flagCC == false)
    <h5>REPORTE CALCULADO MEDIANTE ESTADISTICAS</h5>
    <div style="margin-bottom: 5px; margin-left: 20px">Se tomaron en cuenta los ultimos {{$filtro_cantidad_semanas}} {{$filtro_dias}} para el calculo del promedio </div>
    <h5>Cantidad promedio de comensales de cada menu segun estadistica.</h5>
    @foreach ($cantidades_comensales_estadistica as $menu)
        <div style="margin-bottom: 5px; margin-left: 20px"> {{$menu['menu'].', cantidad:'. $menu['cantidad']}}</div>
    @endforeach
    @else
    <h5>REPORTE CALCULADO MEDIANTE CARGA MANUAL</h5>
    <h5>Cantidad de comensales de cada menu especificados.</h5>
    @foreach ($cantidades_comensales_manual as $menu)
        <div style="margin-bottom: 5px; margin-left: 20px"> {{$menu['menu'].', cantidad:'. $menu['cantidad']}}</div>
    @endforeach    @endif

    <hr>
    @foreach ($menus as $menu => $platos)
        {{$menu}}
        <hr>
        <table width="100%" border="1">
            <thead>
                <tr style="line-height: 14px; font-size: 14px; background: #467FD0">
                    <th style="color:white">Plato</th>
                    <th style="color:white">Insumo</th>
                    <th style="color:white">Estado</th>
                    <th style="color:white">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @if (sizeof($menus)>0)
                    @foreach ($platos as $plato => $insumos)
                        <tr>
                            @if ($plato === array_key_first($platos))
                                <td rowspan="{{count($insumos)}}">
                                    {{$plato}}
                                </td>
                            @else
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
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    @endforeach
</div>
@stop