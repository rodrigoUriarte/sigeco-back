@extends('reportes.pdf2')


@section('content')
<div class="row" style="margin-bottom: 5px">
    <h5 class="text-center"><strong><u style="color:black"> Reporte de Lotes</u></strong></h5>
</div>


<div style="margin-bottom: 5px"><strong> Filtros Aplicados: </strong> </div>
<div style="margin-bottom: 5px">Nombre: {{$filtro_insumo?? 'No aplicado'}}.</div>
<div style="margin-bottom: 5px">Documento: {{$filtro_fecha ?? 'No aplicado' }}.</div>


{{-- <div> <h5>Lista de Materias Primas</h5></div> --}}
<br>
<div class="table" style="font-family: Arial, Helvetica, sans-serif;">
    
    
    <table  class="table table-bordered" >
        <thead>
            <tr style="line-height: 14px; font-size: 14px; background: lightgrey">
                <th>ID</th>
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
                
                <th  >{{$lote->id}} </th>
                <th  >{{$lote->insumo->descripcion}} </th>
                <th  >{{$lote->fecha_vencimiento}} </th>
                <th  >{{$lote->cantidad}} </th>
                <th  >{{$lote->usado}} </th>
                {{-- @if ($proveedor->documento!=null)
                <th  >{{$proveedor->documento->nombre .' - '.$proveedor->numeroDocumento}} </th>
                @else
                <th  >Sin Documento </th>
                @endif
                
                @if ($proveedor->direccion!=null)
                <th >
                        {{ $proveedor->direccion->calle . ' - '.$proveedor->direccion->numero . ' - '.
                        $proveedor->direccion->localidad . ' - '.$proveedor->direccion->provincia . ' - '
                        .$proveedor->direccion->pais }} 
                   
                </th>
                @else
                <th>Sin Direccion </th>
                @endif --}}
                
                
                
            </tr>
            @endforeach
            @endif
        </tbody>
        
    </table>
</div>


@section('cantidad')
{{sizeof($lotes)}}
@endsection
@stop