@extends('reportes.layout')


@section('content')
<div class="invoice">
    <h2>REPORTE LOTES</h2>
    <hr>
    <h4>Filtros Aplicados</h4>
    <div style="margin-bottom: 5px; margin-left: 20px">Insumo: {{$filtro_insumo?? 'No aplicado'}}.</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Vencimiento Desde: {{$filtro_fecha_vencimiento_desde?? 'No aplicado' }}.</div>
    <div style="margin-bottom: 5px; margin-left: 20px">Fecha Vencimiento Hasta: {{$filtro_fecha_vencimiento_hasta?? 'No aplicado' }}.</div>
    <hr>
    <table width="100%">
        <thead>
            <tr style="line-height: 14px; font-size: 14px; background: lightgrey">
                <th>Descripcion</th>
                <th>Fecha Vencimiento</th>
                <th>Cantidad</th>
                <th>Usado </th>
            </tr>
        </thead>
        <tbody>
            @if (sizeof($lotes)>0)
            @foreach ($lotes as $lote)
            <tr>
                <td>{{$lote->insumo->descripcion}} </td>
                <td>{{$lote->fecha_vencimiento_formato}} </td>
                <td style="text-align:right">{{$lote->cantidad}} </td>
                <td>
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
        <tfoot>
            <tr>
                {{-- <td colspan="1"></td>
                <td align="left">Total</td>
                <td align="left" class="gray">€15,-</td> --}}
            </tr>
        </tfoot>
    </table>
</div>
@stop


@section('cantidad')
{{sizeof($lotes)}}
@stop