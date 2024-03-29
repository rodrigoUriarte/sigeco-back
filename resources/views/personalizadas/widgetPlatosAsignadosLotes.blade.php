<h3 style="text-align: center">INSUMOS A UTILIZAR PARA LA PREPARACION DE PLATOS DEL DIA</h3>
@foreach ($pa as $platoAsignado)
<div class="card">
    <div class="card-header"><i class="la la-align-justify"></i>Menu: {{$platoAsignado->menu->descripcion}} - 
        Plato: {{$platoAsignado->plato->descripcion}}</div>
    <div class="card-body">
        <table class="table table-responsive-sm table-sm">
            <thead>
                <tr style="line-height: 14px; font-size: 14px; background: #467FD0">
                    <th style="color:white">Insumo</th>
                    <th style="color:white">Fecha Vencimiento</th>
                    <th style="color:white">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @if (sizeof($pa)>0)
                    @foreach ($platoAsignado->lotes as $lote)
                        <tr>
                            <td>{{$lote->insumo->descripcion}}</td>
                            <td>{{$lote->fecha_vencimiento}}</td>
                            <td>{{$lote->pivot->cantidad}} {{$lote->insumo->unidad_medida}}</td>

                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    </div>
@endforeach
