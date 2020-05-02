@extends(backpack_view('blank'))

@php
$widgets['before_content'][] =
[
'type' => 'alert',
'class' => 'alert alert-success',
'heading' => 'Usted se ha logueado como:',
'content' => backpack_user()->name,
'close_button' => false, // show close button or not
];

$widgets['before_content'][] =
[
'type' => 'jumbotron',
'heading' => 'BIENVENIDO!',
'content' => 'En la barra lateral encontrara las operaciones disponibles',
'button_link' => backpack_url('logout'),
'button_text' => trans('backpack::base.logout'),
];

@endphp
@if (backpack_user()->hasRole('operativo'))
@php
$pa =\App\Models\PlatoAsignado::where('comedor_id', backpack_user()->persona->comedor_id)
    ->where('fecha', Carbon\Carbon::now()->toDateString())
    ->get(); 
@endphp
@if (count($pa) > 0)
    @php
        $widgets['before_content'][] =
        [
        'type'        => 'view',
        'view'        => 'personalizadas.widgetPlatosAsignadosLotes',
        'pa'    => $pa,
        ];
    @endphp
@endif
@endif



@section('content')
@endsection