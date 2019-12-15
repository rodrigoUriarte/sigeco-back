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

$widgets['after_content'][] =
[
'type' => 'jumbotron',
'heading' => 'BIENVENIDO!',
'content' => 'En la barra lateral encontrara las operaciones disponibles',
'button_link' => backpack_url('logout'),
'button_text' => trans('backpack::base.logout'),
];
@endphp

@section('content')
@endsection