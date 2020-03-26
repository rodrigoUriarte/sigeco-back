@extends(backpack_view('blank'))

@section('content')

@foreach (Alert::getMessages() as $type => $messages)
@foreach ($messages as $message)
<div class="alert alert-{{ $type }}">{{ $message }}</div>
@endforeach
@endforeach

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<h2>ESTIMACION COMPRA</h2>

<div class="row">
    <div class="card" style="width:100%">
        <div class="card-body">
            <form action="{{route('reporteCalculoEstimacionCompra')}}" method="GET" enctype="multipart/form-data">
                <h5>Calculo estimativo para realizar compras</h5>
                <hr>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Dia <span style="color: red">*</span></label>
                        <select class="form-control" name="filtro_dias" required="required" id="filtro_dias">
                            <option value="">Seleccione un dia</option>
                            @if($dias)
                            @foreach($dias as $dia)
                            <option>{{$dia->dia}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Semanas anteriores p/estadistica: </label>
                        <input class="form-control" type="number" name="filtro_cantidad_semanas"
                            id="filtro_cantidad_semanas" min="1" max="4"
                            placeholder="Semanas anteriores para calcular la estadistica" style="width: 100%;">
                    </div>
                </div>
                <hr>
                @if ($menus)
                <div class="row">
                    @foreach ($menus as $menu)
                    <div class="form-group col-md-6">
                        <label>Tipo Menu</label>
                        <input class="form-control" type="text" name="filtro_menu[{{$menu->id}}]" id="filtro_menu"
                            value="{{$menu->descripcion}}" readonly style="width: 100%;">
                        {{-- <select class="form-control" name="filtro_menu[]" id="filtro_menu[]">
                            <option value="">Seleccione un tipo de menu</option>
                            @foreach($menus as $menu)
                            <option>{{$menu}}</option>
                        @endforeach
                        </select> --}}
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

            </form>
        </div>


        {{-- <div class="card-body">
            {!! $inscripciones->container() !!}
        </div> --}}

    </div>

</div>

<script>
    $(document).ready(function() {
        $('[id="filtro_cantidad_semanas"]').focus(function() {
            // $('[name="filtro_menu[]"]').each(function () {
            //     this.value=""
            // });
    
            $('[id="filtro_menu_cantidad"]').each(function () {
                this.value=""
            });
        });

        // $('[name="filtro_menu[]"]').focus(function() {
        //     $('[name="filtro_cantidad_semanas"]').each(function () {
        //         this.value=""
        //     });
        // });

        $('[id="filtro_menu_cantidad"]').focus(function() {
            $('[id="filtro_cantidad_semanas"]').each(function () {
                this.value=""
            });
        });

    });

</script>

{{-- 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js">
</script>

{!! $inscripciones->script() !!} --}}

@endsection