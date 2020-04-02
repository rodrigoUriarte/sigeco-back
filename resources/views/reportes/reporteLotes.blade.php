@extends('reportes.layout')


@section('content')
<div class="invoice">
    <h2>REPORTE LOTES</h2>
    <hr>
    <h4>Filtros Aplicados</h4>
    <div style="margin-bottom: 5px; margin-left: 20px">Insumo: {{$filtro_insumo?? 'No aplicado'}}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Vencimiento Desde: {{$filtro_fecha_vencimiento_desde?? 'No aplicado' }}</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Vencimiento Hasta: {{$filtro_fecha_vencimiento_hasta?? 'No aplicado' }}</div>
    @if ($filtro_lotes_vacios)
    <div style="margin-bottom: 5px; margin-left: 20px">Incluir Lotes Vacios: SI</div>
    @else
    <div style="margin-bottom: 5px; margin-left: 20px">Incluir Lotes Vacios: NO</div>
    @endif
    <hr>
    <table width="100%" border="1">
        <thead>
            <tr style="line-height: 14px; font-size: 14px; background: #467FD0">
                <th style="color:white">Insumo</th>
                <th style="color:white">Fecha Vencimiento</th>
                <th style="color:white">Cantidad</th>
                <th style="color:white">Usado </th>
            </tr>
        </thead>
        <tbody>
            @if (sizeof($lotes)>0)
            @foreach ($lotes as $lote)
            <tr>
                <td>{{$lote->insumo->descripcion}} </td>
                <td style="text-align:right">{{$lote->fecha_vencimiento_formato}} </td>
                <td style="text-align:right">{{$lote->cantidad}} </td>
                <td style="text-align:right">
                    @if($lote->usado == 0)
                    NO
                    @elseif ($lote->usado == 1)
                    SI
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <div style="margin-bottom: 5px; margin-left: 20px">Cantidad de Lotes: {{sizeof($lotes)}}</div>
</div>
@stop