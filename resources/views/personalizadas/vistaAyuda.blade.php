@extends(backpack_view('blank'))

@section('content')


<h2>AYUDA</h2>
<div class="row">

  @if(backpack_user()->hasRole('operativo'))
  <div class="card" style="width:100%">

    <div class="card-header">
      <h3>AYUDA PARA OPERATIVOS</h3>
    </div>

    <div class="card-body">
      <table style="text-align: left; width: 100%; margin-left: auto; margin-right: auto;" border="3" cellpadding="2"
        cellspacing="2">
        <caption><span style="font-weight: bold;">VIDEOS</span><br></caption>
        <tbody>
          <tr>
            <td align="center">Como agregar una asistencia?</td>
            <td align="center">Como agregar una banda horaria?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/_2hjA-A6V-Q" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/gS2Xa7LkrI8" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

          <tr>
            <td align="center">Como agregar un plato asignado para un determinado dia?</td>
            <td align="center">Como agregar un ingreso de un insumo?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/eocco5BjBbs" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/0G3gJiSoWWE" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>
          
          <tr>
            <td align="center">Como agregar un insumo para la preparacion de un plato?</td>
            <td align="center">Como agregar una persona y usuario comensal nuevos?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/ABYAFXw-MkI" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/nqBmWHtn-dw" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

          <tr>
            <td align="center">Como agregar una regla pàra aplicar sanciones?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/yLyEUB47gGI" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
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
      <h3>AYUDA PARA COMENSALES</h3>
    </div>


    <div class="card-body">
      <table style="text-align: left; width: 100%; margin-left: auto; margin-right: auto;" border="3" cellpadding="2"
        cellspacing="2">
        <caption><span style="font-weight: bold;">VIDEOS</span><br></caption>
        <tbody>
          <tr>
            <td align="center">Como agregar un menu asignado?</td>
            <td align="center">Como agregar una inscripcion?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/RVDKtShctZA" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/CMlJJnUhJjk" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>
          
        </tbody>
      </table>
    </div>
  </div>
  @endif

  @if(backpack_user()->hasRole('superadministrador'))
  <div class="card" style="width:100%">

    <div class="card-header">
      <h3>AYUDA PARA SUPERADMINISTRADOR</h3>
    </div>


    <div class="card-body">
      <table style="text-align: left; width: 100%; margin-left: auto; margin-right: auto;" border="3" cellpadding="2"
        cellspacing="2">
        <caption><span style="font-weight: bold;">VIDEOS</span><br></caption>
        <tbody>
          <tr>
            <td align="center">Como agregar un unidad academica?</td>
            <td align="center">Como agregar una persona y usuario con rol operativo?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/kzOWr3WGZtA" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/XA9oluXHflM" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>

          <tr>
            <td align="center">Como agregar un comedor?</td>
          </tr>
          <tr>
            <td align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/KnYUKmuKEn0" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe></td>
          </tr>
          
        </tbody>
      </table>
    </div>
  </div>
  @endif

</div>


@endsection