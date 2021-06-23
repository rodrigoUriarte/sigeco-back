@extends(backpack_view('blank'))

@section('content')


<div class="row">

  @if(backpack_user()->hasRole('operativo'))
  <div class="card border-info mb-3" style="width:100%">

    <div class="card-header">
      <p class="h3 m-0">VIDEOS DE AYUDA</p>
    </div>

    <div class="card-body p-3">

      <div class="card-deck mb-3">
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/_2hjA-A6V-Q"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar una asistencia?</h5>
          </div>
        </div>
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/gS2Xa7LkrI8"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar una banda horaria?</h5>
          </div>
        </div>
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/eocco5BjBbs"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar un plato asignado para un determinado dia?</h5>
          </div>
        </div>
      </div>

      <div class="card-deck mb-3">
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/HBb395Q7aEQ"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar un remito?</h5>
          </div>
        </div>
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/yLyEUB47gGI"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar una regla para aplicar sanciones?</h5>
          </div>
        </div>
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/qmH9pWBhPgE"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar un plato?</h5>
          </div>
        </div>
      </div>

      <div class="card-deck mb-3">
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/GcVRU7B1Xv4"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar los datos de parametro?</h5>
          </div>
        </div>
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/1T14o1qnAAY"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar una justificacion?</h5>
          </div>
        </div>
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/bh_2c5ZlmSQ"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar un dia de servicio?</h5>
          </div>
        </div>
      </div>

      <div class="card-deck mb-3">
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/NfqGZb99rjg"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como hacer una estimacion de compra?</h5>
          </div>
        </div>
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/nqBmWHtn-dw"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar una persona y usuario comensal nuevos?</h5>
          </div>
        </div>
      </div>

    </div>
  </div>
  @endif


  @if(backpack_user()->hasRole('comensal'))

  <div class="card border-info mb-3" style="width:100%">

    <div class="card-header">
      <p class="h3 m-0">VIDEOS DE AYUDA</p>
    </div>

    <div class="card-body p-3">

      <div class="card-deck mb-3">
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/RVDKtShctZA"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar un menu asignado?</h5>
          </div>
        </div>
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/GP1n1Lx9srE"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar un dia de preferencia?</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  @if(backpack_user()->hasRole('superAdmin'))


  <div class="card border-info mb-3" style="width:100%">

    <div class="card-header">
      <p class="h3 m-0">VIDEOS DE AYUDA</p>
    </div>

    <div class="card-body p-3">

      <div class="card-deck mb-3">
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/kzOWr3WGZtA"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar un unidad academica?</h5>
          </div>
        </div>
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/XA9oluXHflM"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar una persona y usuario con rol operativo?</h5>
          </div>
        </div>
      </div>

      <div class="card-deck mb-3">
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/KnYUKmuKEn0"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar un comedor?</h5>
          </div>
        </div>
        <div class="card border-info">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/69kyh5t_IWc"></iframe>
          </div>
          <div class="card-body">
            <h5 class="card-title">Como agregar un proveedor?</h5>
          </div>
        </div>
      </div>

    </div>
  </div>

  @endif

</div>


@endsection