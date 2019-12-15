<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte SGC</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .invoice table {
            margin: 15px;
        }

        .invoice h4 {
            margin-left: 15px;
        }

        .invoice h3 {
            margin-left: 15px;
        }

        .invoice h2 {
            margin-left: 15px;
        }

        .information {
            background-color: #60A7A6;
            color: #FFF;
        }

        #container {
            width: 100%;
        }

        #left {
            float: left;
            width: 40%;
        }

        #right {
            float: right;
            width: 40%;
        }

        #center {
            margin: 0 auto;
            width: 15%;
        }
    </style>

</head>



<body>
    <div class="information">
        <div style="text-align:center">
            <h3>{{backpack_user()->persona->comedor->unidadAcademica->nombre}}</h2>
        </div>
        <div id="container">
            <div id="left">
                <h4 style="text-align:center">{{backpack_user()->persona->comedor->descripcion}}</h3>
                <h5 style="text-align:center">{{backpack_user()->persona->comedor->direccion}}</h4>
            </div>
            <div id="right">
                <h5 style="text-align:center">Usuario: {{backpack_user()->name}}</h4>
            </div>
            <div id="center">
                <img src="{{asset("storage/logoUNAM.png")}}" alt="Logo" width="120" class="logo" />
            </div>
        </div>
        {{-- <table width="100%">
            <tr>
                <td align="center" colspan="3">
                    <h2>{{backpack_user()->persona->comedor->unidadAcademica->nombre}}</h2>
        </td>
        </tr>
        <tr>
            <td align="left" style="width: 40%;">
                <h3 style="text-align:center">{{backpack_user()->persona->comedor->descripcion}}</h3>
                <h4 style="text-align:center">{{backpack_user()->persona->comedor->direccion}}</h4>
            </td>

            <td align="center">
                <img src="{{asset("storage/logoUNAM.png")}}" alt="Logo" width="120" class="logo" />
            </td>

            <td align="right" style="width: 40%;">
                <h4 style="text-align:center">Usuario: {{backpack_user()->name}}</h4>
            </td>
        </tr>
        </table> --}}
    </div>

    <section>
        @yield('content')
    </section>

    <div class="invoice">
        <h4><strong>Cantidad de registros: </strong> @yield('cantidad')</h4>
    </div>

    <div class="information" style="position: absolute; bottom: 0;">
        <table width="100%">
            <tr>
                <td align="left" style="width: 50%;">
                    Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y')}}
                </td>
            </tr>

        </table>
    </div>
</body>



</html>