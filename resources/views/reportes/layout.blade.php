<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte SGC</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
            border-collapse: collapse;
        }

        .invoice table {
            margin: 10px;
        }

        .invoice h4 {
            margin-left: 15px;
        }

        .invoice h2 {
            margin-left: 15px;
        }

        .information {
            background-color: #467FD0;
            color: white;
        }

        #container {
            width: 100%;
        }

        #left {
            float: left;
            width: 30%;
        }

        #right {
            float: right;
            width: 30%;
        }

        #center {
            margin: 0 auto;
            width: 20%;
        }
    </style>

</head>

<body>
    <div class="information">
        <div style="text-align:center">
            <h3>{{backpack_user()->persona->comedor->unidadAcademica->nombre}}</h3>
        </div>
        <div id="container">
            <div id="left">
                <h4 style="text-align:center">{{backpack_user()->persona->comedor->descripcion}}</h4>
                <h5 style="text-align:center">{{backpack_user()->persona->comedor->direccion}}</h5>
            </div>
            <div id="right">
                <h5 style="text-align:center">Usuario: {{backpack_user()->name}}</h5>
            </div>
            <div id="center">
                <img src="{{asset("storage/unam.jpg")}}" alt="Logo" width="200" class="logo" />
            </div>
        </div>
    </div>

    <section>
        @yield('content')
    </section>

    <section>
        <div class="invoice">
            <h4><strong>Cantidad de registros: </strong> @yield('cantidad')</h4>
        </div>
    </section>

    <div style="position: absolute; bottom: 15;">
        <table width="100%">
            <tr>
                <td align="left" style="width: 50%;">
                    Fecha: {{Carbon\Carbon::now()->format('d/m/Y')}}
                </td>
            </tr>
        </table>
    </div>

</body>



</html>