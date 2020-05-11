@extends(backpack_view('blank'))

@php
$defaultBreadcrumbs = [
trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
$crud->entity_name_plural => url($crud->route),
trans('backpack::crud.list') => false,
];

// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/i18n/es.js"></script>
</head>

  @section('header')
  <div class="container-fluid">
    <h2>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
    </h2>
  </div>
  @endsection

  @section('content')

  @foreach (Alert::getMessages() as $type => $messages)
  @foreach ($messages as $message)
  <div class="alert alert-{{ $type }}">{{ $message }}</div>
  @endforeach
  @endforeach

  <!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      <div class="">


        <div class="card text-left">

          <div class="card-header">

            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample"
              aria-expanded="false" aria-controls="collapseExample">
              GENERAR REPORTE
            </button>
          </div>


          <div class="collapse" id="collapseExample">
            <div class="card-body">
              <form action="{{route('menusAsignados.reporteMenusAsignados')}}"
                target="_blank" method="GET" enctype="multipart/form-data">
                <h5>FILTROS</h5>
                <hr>
                <div class="row">
                  <div class="form-group col-md-3">
                    <label>Menu:</span></label>
                    <select class="form-control" name="filtro_menu" id="filtro_menu">
                      <option></option>
                      @if($menus)
                      @foreach($menus as $menu)
                      <option>{{$menu->descripcion}}</option>
                      @endforeach
                      @endif
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Comensal:</label><br>
                    <select class="form-control" name="filtro_comensal" id="filtro_comensal">
                      <option></option>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Fecha Desde: </label>
                    <input class="form-control" type="date" name="filtro_fecha_desde"
                      id="filtro_fecha_vencimiento_desde" style="width: 100%;">
                  </div>
                  <div class="form-group col-md-3">
                    <label>Fecha Hasta: </label>
                    <input class="form-control" type="date" name="filtro_fecha_hasta"
                      id="filtro_fecha_vencimiento_hasta" style="width: 100%;">
                  </div>
                </div>
                <hr>
                @csrf
                <div align="right">
                  <button type="submit" class="btn  btn-success  btn-flat btn-sm">Generar Reporte</button>
                </div>
              </form>
            </div>

          </div>



        </div>

        <div class="row mb-0">
          <div class="col-6">
            @if ( $crud->buttons()->where('stack', 'top')->count() || $crud->exportButtons())
            <div class="hidden-print {{ $crud->hasAccess('create')?'with-border':'' }}">

              @include('crud::inc.button_stack', ['stack' => 'top'])

            </div>
            @endif
          </div>
          <div class="col-6">
            <div id="datatable_search_stack" class="float-right"></div>
          </div>
        </div>

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
        @include('crud::inc.filters_navbar')
        @endif

        <div class="overflow-hidden mt-2">

          <table id="crudTable" class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs"
            cellspacing="0">
            <thead>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns() as $column)
                <th data-orderable="{{ var_export($column['orderable'], true) }}"
                  data-priority="{{ $column['priority'] }}"
                  data-visible="{{ var_export($column['visibleInTable'] ?? true) }}"
                  data-visible-in-modal="{{ var_export($column['visibleInModal'] ?? true) }}"
                  data-visible-in-export="{{ var_export($column['visibleInExport'] ?? true) }}">
                  {!! $column['label'] !!}
                </th>
                @endforeach

                @if ( $crud->buttons()->where('stack', 'line')->count() )
                <th data-orderable="false" data-priority="{{ $crud->getActionsColumnPriority() }}"
                  data-visible-in-export="false">{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns() as $column)
                <th>{!! $column['label'] !!}</th>
                @endforeach

                @if ( $crud->buttons()->where('stack', 'line')->count() )
                <th>{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </tfoot>
          </table>

          @if ( $crud->buttons()->where('stack', 'bottom')->count() )
          <div id="bottom_buttons" class="hidden-print">
            @include('crud::inc.button_stack', ['stack' => 'bottom'])

            <div id="datatable_button_stack" class="float-right text-right hidden-xs"></div>
          </div>
          @endif

        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div>
  </div>

  <script type="text/javascript">
    //$(document).ready(function() {
    $.fn.select2.defaults.set('language', 'es');
    $('[id="filtro_comensal"]').select2({
      placeholder: 'Seleccione un comensal',
      width: '100%',
      allowClear: true,
      ajax: {
        url: '/admin/getComensalesComedor',
        dataType: 'json',
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
      }
    });

    $('[id="filtro_menu"]').select2({
      placeholder: 'Seleccione un menu',
      width: '100%',
      allowClear: true,
    });
  //});
  </script>
  @endsection

  @section('after_styles')
  <!-- DATA TABLES -->
  <link rel="stylesheet" type="text/css"
    href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css"
    href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css"
    href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

  <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/list.css') }}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
  @endsection

  @section('after_scripts')
  @include('crud::inc.datatables_logic')

  <script src="{{ asset('packages/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('packages/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('packages/backpack/crud/js/list.js') }}"></script>
  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
  @endsection