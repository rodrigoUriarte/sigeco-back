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
            @foreach ($mpifc as $menus => $platos)
            <tr>
                <td>
                    {{$menus}}
                </td>
            </tr>

            @foreach ($platos as $plato => $insumos)
            <td>
                <tr>
                    <td>
                        {{$plato}}
                    </td>
                </tr>
            </td>

            @foreach ($insumos as $insumo => $value)
            <?php xdebug_break(); ?>
            <td>
            <td>
                <tr>
                    <td>
                        {{$insumo}}
                    </td>
                    <td>
                        @if ($value->pluck('estado')->first() == true)
                        REMANENTE:
                        @endif
                        @if ($value->pluck('estado')->first() == false)
                        FALTANTE:
                        @endif
                    </td>
                    <td align="right">{{$value->pluck('cantidad')->first()}}</td>
                </tr>
            </td>
            </td>
            @endforeach
            @endforeach
            @endforeach
            @endif
        </tbody>
    </table>
</div>
@stop


@section('cantidad')
{{sizeof($platos)}}
@stop