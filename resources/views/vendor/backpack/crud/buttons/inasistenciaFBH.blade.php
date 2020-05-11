@if ($crud->hasAccess('inasistenciaFBH') && $crud->get('list.bulkActions'))
<a href="javascript:void(0)" onclick="inasistenciaFBH(this)" class="btn btn-sm btn-secondary bulk-button"><i
    class="la la-check"></i>No Asistio (FBH)</a>
@endif
@foreach (Alert::getMessages() as $type => $messages)
@foreach ($messages as $message)
<div class="alert alert-{{ $type }}">{{ $message }}</div>
@endforeach
@endforeach

@push('after_scripts')
<script>
  if (typeof inasistenciaFBH != 'function') {
    function inasistenciaFBH(button) {

        if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0)
        {
            new Noty({
            type: "warning",
            text: "<strong>{{ trans('backpack::crud.bulk_no_entries_selected_title') }}</strong><br>{{ trans('backpack::crud.bulk_no_entries_selected_message') }}"
          }).show();

          return;
        }

        var message = "Esta seguro que desea cambiar el estado de :number 'asistencias' a 'inasistencias' ?";
        message = message.replace(":number", crud.checkedItems.length);

        // show confirm message
        swal({
        title: "{{ trans('backpack::base.warning') }}",
        text: message,
        icon: "warning",
        buttons: {
          cancel: {
          text: "{{ trans('backpack::crud.cancel') }}",
          value: null,
          visible: true,
          className: "bg-secondary",
          closeModal: true,
        },
          delete: {
          text: "Aceptar",
          value: true,
          visible: true,
          className: "bg-primary",
        }
        },
      }).then((value) => {
        if (value) {
          var ajax_calls = [];
          var noAsistio_route = "{{ url($crud->route) }}/bulk-noAsistio";

          // submit an AJAX delete call
          $.ajax({
            url: noAsistio_route,
            type: 'POST',
            data: { entries: crud.checkedItems },
            success: function(result) {
              Object.keys(result).forEach(function(status) {
              //result.forEach(function(status) {
                if (status == false) {
                  Object.keys(result[status]).forEach(function(message) {
                    console.log(status)
                    new Noty({
                    timeout: false,
                    type: "info",
                    text: "<strong>Algunos datos no son validos</strong><br>"
                      +Object.keys(result[status][message]).length
                      +" Registros no se pueden modificar porque no cumplen la siguiente condicion: <br>"
                      +message
                    }).show();
                  });   
                }

                if (status == true) {
                  Object.keys(result[status]).forEach(function(message) {
                    new Noty({
                    timeout: false,
                    type: "success",
                    text: "<strong>Datos actualizados</strong><br>"
                      +Object.keys(result[status][message]).length+" "
                      +message
                    }).show();
                  });              
                }         
              });
              crud.checkedItems = [];
              crud.table.ajax.reload();                   
            },
            
            error: function(result) {
              // Show an alert with the result
                    new Noty({
                    type: "error",
                    text: "<strong>Operacion Fallo</strong><br>"
                  }).show();
            }
          });
        }
      });
      }
  }
</script>
@endpush