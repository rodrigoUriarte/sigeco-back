@extends(backpack_view('blank'))

@section('content')


<h2>ESTADISTICAS</h2>
<div class="row">


    <div class="card" style="width:50%">

        <div class="card-header">
            <form action="{{route('estadisticas')}}" method="GET" enctype="multipart/form-data">

                <h5>Cantidad Inscripciones</h5>
                <hr>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Fecha Desde : </label>
                        <input class="form-control" type="date" name="filtro_fecha_desde" id="filtro_fecha_desde"
                            placeholder="Ingrese una fecha de inscripcion a filtrar" style="width: 100%;">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Fecha Hasta : </label>
                        <input class="form-control" type="date" name="filtro_fecha_hasta" id="filtro_fecha_hasta"
                            placeholder="Ingrese una fecha de inscripcion a filtrar" style="width: 100%;">
                    </div>
                </div>
                <hr>
                @csrf
                <div align="right">
                    <button type="submit" class="btn  btn-success  btn-flat btn-sm">Filtrar</button>
                </div>
            </form>
        </div>


        <div class="card-body">
            {!! $inscripciones->container() !!}
        </div>
    </div>
    {{-- <div class="card" style="width:50%">
        <div class="card-header">
            <h5>TituloEstaditica</h5>
            <hr>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Fecha Inscripcion : </label>
                    <input class="form-control" type="date" name="filtro_fecha_inscripcion"
                        id="filtro_fecha_inscripcion" placeholder="Ingrese una fecha de inscripcion a filtrar"
                        style="width: 100%;">
                </div>
            </div>
            <hr>
            <div align="right">
                <button type="submit" class="btn  btn-success  btn-flat btn-sm">Generar Reporte</button>
            </div>
        </div>
        <div class="card-body">
            {!! $inscripciones->container() !!}
        </div>
    </div> --}}
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
{!! $inscripciones->script() !!}


@endsection