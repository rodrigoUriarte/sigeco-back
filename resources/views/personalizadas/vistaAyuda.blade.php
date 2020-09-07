@extends(backpack_view('blank'))

@section('content')


<div class="row">

  @if(backpack_user()->hasRole('operativo'))
  <div class="card" style="width:100%">

    <div class="card-header">
      <h3>VIDEOS DE AYUDA</h3>
    </div>

    <div class="card-body">
      <table style="text-align: left; width: 100%; margin-left: auto; margin-right: auto;" border="3" cellpadding="2"
        cellspacing="2">
        <tbody>
          <tr>
            <td align="center">Como agregar una asistencia?</td>
            <td align="center">Como agregar una banda horaria?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/_2hjA-A6V-Q"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/gS2Xa7LkrI8"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

          <tr>
            <td align="center">Como agregar un plato asignado para un determinado dia?</td>
            <td align="center">Como agregar un remito?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/eocco5BjBbs"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/HBb395Q7aEQ"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

          <tr>
            <td align="center">Como agregar una regla para aplicar sanciones?</td>
            <td align="center">Como agregar un plato?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/yLyEUB47gGI"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/qmH9pWBhPgE"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

          <tr>
            <td align="center">Como agregar los datos de parametro?</td>
            <td align="center">Como agregar una justificacion?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/GcVRU7B1Xv4"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/1T14o1qnAAY"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

          <tr>
            <td align="center">Como agregar un dia de servicio?</td>
            <td align="center">Como hacer una estimacion de compra?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/bh_2c5ZlmSQ"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/NfqGZb99rjg"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

          <tr>
            <td align="center">Como agregar una persona y usuario comensal nuevos?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/nqBmWHtn-dw"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>
  @endif


  @if(backpack_user()->hasRole('comensal'))
  <div class="card" style="width:100%">

    <div class="card-header">
      <h3>VIDEOS DE AYUDA</h3>
    </div>


    <div class="card-body">
      <table style="text-align: left; width: 100%; margin-left: auto; margin-right: auto;" border="3" cellpadding="2"
        cellspacing="2">
        <tbody>
          <tr>
            <td align="center">Como agregar un menu asignado?</td>
            <td align="center">Como agregar un dia de preferencia?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/RVDKtShctZA"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/GP1n1Lx9srE"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>
  @endif

  @if(backpack_user()->hasRole('superAdmin'))
  <div class="card" style="width:100%">

    <div class="card-header">
      <h3>VIDEOS DE AYUDA</h3>
    </div>


    <div class="card-body">
      <table style="text-align: left; width: 100%; margin-left: auto; margin-right: auto;" border="3" cellpadding="2"
        cellspacing="2">
        <tbody>
          <tr>
            <td align="center">Como agregar un unidad academica?</td>
            <td align="center">Como agregar una persona y usuario con rol operativo?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/kzOWr3WGZtA"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/XA9oluXHflM"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

          <tr>
            <td align="center">Como agregar un comedor?</td>
            <td align="center">Como agregar un proveedor?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/KnYUKmuKEn0"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/69kyh5t_IWc"
                frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>
  @endif

</div>


@endsection