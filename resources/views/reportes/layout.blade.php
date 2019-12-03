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

        .information .logo {
            margin: 5px;
        }

        .information table {
            padding: 10px;
        }
    </style>
</head>



<body>
    <div class="information">
        <table width="100%">
            <tr>
                <td align="left" style="width: 40%;">
                    <h2>{{backpack_user()->persona->comedor->unidadAcademica->nombre}}</h2>
                    <h3>{{backpack_user()->persona->comedor->descripcion}}</h3>
                    <h4>Direccion Comedor: {{backpack_user()->persona->comedor->direccion}}</h4>
                </td>

                <td align="center">
                    <img src="{{asset("storage/logoUNAM.png")}}" alt="Logo" width="85" class="logo" />
                </td>

                <td align="right" style="width: 40%;">
                    <h4>Usuario: {{backpack_user()->name}}</h4>
                </td>
            </tr>
        </table>
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
                <td align="right" style="width: 50%;">

                </td>
            </tr>

        </table>
    </div>
</body>



</html>