<!DOCTYPE>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SISTEMA DE GESTION DE COMEDORES</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">    
</head>
<style>
    body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif;
        font-size: 14px;
        /*font-family: SourceSansPro;*/
    }
    .texto{
        font-size: 14px;
    }
    #logo{
        /* float: left; */
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
    }
    
    #imagen{
        width: 200px;
    }
    
    #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
    }
    
    #encabezado{
        text-align: center;
        margin-left: 5%;
        margin-right: 44%;
        font-size: 15px;
    }
    
    #fecha{
        /*position: relative;*/
        float: right;
        margin-top: -3%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 14px;
    }
    #datosDerecha{
        float: right;
        margin-top: -3%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 14px;
    }
    
    #user{
        /*position: relative;*/
        float: right;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: -19%;
        font-size: 10px;
    }
    
    section{
        clear: left;
    }
    
    #cliente{
        text-align: left;
    }
    
    #titulo{
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
    }
    
    #fv, #fac{
        color: #000000;
        font-size: 15px;
    }
    #fa{
        color: #000000;
        font-size: 15px;
    }
    
    #facliente thead{
        padding: 20px;
        background: #FFFFFF;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;
    }
    
    #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
    }
    
    #facvendedor thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
    }
    
    #lista{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
    }
    
    #lista thead{
        padding: 20px;
        background: #000000;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;
    }
    
    #footer {
        bottom: 0;
        margin: 0 -2cm;
        border-top: 0pt solid #1BE359FF;
    }
    
    footer {
        position: fixed;
        bottom: -50px;
        left: -5px;
        right: -5px;
        height: 50px;
        
        /** Extra personal styles **/
        background-color: #FFFFFF;
        color: black;
        text-align: right;
        line-height: 35px;
    }
    /* .page-number {
        text-align: right;
        color: #fff;
        margin: -1.4cm 1.5cm;
    }
    
    .infor {
        text-align: center;
        color: #fff;
    }
    .infor p{
        margin-top: -1.4cm;
    } */
    
    
    .page-number:before {
        content: "Pag. " counter(page);
    }
    
</style>

<body>
    <br><br>
    
    <header>
            <div id="fecha">
                    <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y')}}</p>
                </div>
        <div id="logo" class="">
            <a ><img id="imagen" class="float-left rounded " src="{{asset("storage/logoUNAM.png")}}"> </a>
        </div>
        {{-- <div id="datos">
            <p id="encabezado">
                <b>COOPERATIVA DE AGUA POTABLE</b><br>Av. 9 de Julio 1368, San Jose - Misiones, Argentina<br>Telefono:(+54)3758655665<br>Email:coop_agua@gmail.com
            </p>
        </div> --}}
        
        <div id="datosDerecha" class="border ">
            <div >Numero Reporte: _ _ _ _ _ _  </div>
            <div >Direccion: _ _ _ _ _ _ _ _ _ _ </div>
            <div >Telefono Empresa: 3752-_ _ _ _ _ _</div>
        </div>
<br>
<br>
<br>
<br>
<br>
<br>
    
    <div id="">
        <div >Filtros Aplicados:  </div>
        @yield('filtrosAplicados')
        
    </div>

        
        {{-- <div id="fecha">
            <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y')}}</p>
        </div>
        
        <div id="user">
            Generado por: {{ Auth::user()->name . ' ' . Auth::user()->apellido }}
        </div> --}}
        
        {{-- <div class="container ">
            <div class="row">
                <div class="col border " >
                    
                    <div id="logo">
                        <a ><img id="imagen" class="float-left rounded " src="{{ asset('images/logo-mygsublimacion.jpg') }}"> </a>
                    </div>
                </div>
                
                <div class="col border ">
                    
                    <div class="row">
                        <div class="col border ">
                            <h3>MyG Sublimación</h3>
                        </div>
                        
                    </div>
                    
                    <div class="row" >
                        <div class="col-8 border ">YO SOY IROMAN</div>
                        <div class="col border ">Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y')}}</div>
                    </div>
                </div>
            </div>
        </div> --}}
    </header>
    <br>
    <section>
        <br>
        @yield('content')
    </section>
    <br>
    <br>
    <div class="izquierda">
        <p><strong>Cantidad Total de Registrados: </strong> @yield('cantidad')</p>
    </div>
    
    <footer>
        @yield('footer')
        <div class="page-number"></div>
    </footer>
    
    
</body>
</html>

{{-- <script src="{{asset('admin_panel/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script> --}}
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
