@extends(backpack_view('blank'))

@section('content')

@foreach (Alert::getMessages() as $type => $messages)
@foreach ($messages as $message)
<div class="alert alert-{{ $type }}">{{ $message }}</div>
@endforeach
@endforeach

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
        hr.style-two {
            border: 0;
            height: 1px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
        }
    </style>
</head>

<h2>ESTIMACION COMPRA</h2>

<div class="row">
    <div class="card" style="width:100%">
        <form action="{{route('reporteCalculoEstimacionCompra')}}"
            target="_blank" method="GET" enctype="multipart/form-data">
            <div class="card-body">
                <h5>Mediante Estadistica</h5>
                <hr>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Dia:</span></label>
                        <select class="form-control" name="filtro_dias" id="filtro_dias">
                            <option value="">Seleccione un dia</option>
                            @if($dias)
                            @foreach($dias as $dia)
                            <option>{{$dia->dia}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Semanas anteriores para el calculo de la estadistica: </label>
                        <input class="form-control" type="number" name="filtro_cantidad_semanas"
                            id="filtro_cantidad_semanas" min="1" max="4"
                            placeholder="Semanas anteriores para calcular la estadistica" style="width: 100%;">
                    </div>
                </div>
            </div>
            <hr class="style-two">
            <div class="card-body">
                <h5>Mediante Cantidad Comensales</h5>
                <hr>
                @if ($menus)
                <div class="row">
                    @foreach ($menus as $menu)
                    <div class="form-group col-md-6">
                        <label>Tipo Menu</label>
                        <input class="form-control" type="label" name="filtro_menu[{{$menu->id}}]" id="filtro_menu"
                            value="{{$menu->descripcion}}" readonly style="width: 100%;">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Cantidad comensales menu: </label>
                        <input class="form-control" type="number" name="filtro_menu_cantidad[{{$menu->id}}]"
                            id="filtro_menu_cantidad" min="1" placeholder="Cantidad de comensales de este menu"
                            style="width: 100%;">
                    </div>
                    @endforeach
                </div>
                @endif

                <hr>
                @csrf
                <div align="right">
                    <button type="submit" class="btn  btn-success  btn-flat btn-sm">Generar Reporte</button>
                </div>
            </div>
        </form>

    </div>

</div>

<script>
    $(document).ready(function() {
        $('[id="filtro_dias"]').focus(function() {
    
            $('[id="filtro_menu_cantidad"]').each(function () {
                this.value=""
            });
        });
        $('[id="filtro_cantidad_semanas"]').focus(function() {
    
            $('[id="filtro_menu_cantidad"]').each(function () {
                this.value=""
            });
        });

        $('[id="filtro_menu_cantidad"]').focus(function() {
            $('[id="filtro_dias"]').each(function () {
                this.value=""
            });
            $('[id="filtro_cantidad_semanas"]').each(function () {
                this.value=""
            });
        });

    });

</script>


@endsection