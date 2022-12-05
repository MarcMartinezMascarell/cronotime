@extends('layouts.app')

@section('content')

@include('users.partials.header', [
    'title' => 'Changelog',
    'description' => __('A continuación encontrarás una lista detallada de todos los cambios realizados en la aplicación'),
])

<div class="container-fluid mt-4">
    <ul >
        <strong>v1.0.1</strong>
        <li>Contador de <em>horas trabajadas hoy</em> y <em>horas trabajadas esta semana</em> ahora se actualiza automáticamente, sin necesidad de recargar la págna.</li>
        <li>Añadidos botones enlaces directos a "Semana anterior" y "Mes anterior" en el apartado de informes.</li>
        <li>Aumentado el tiempo de sesión a 7 días.</li>
        <li>Registro de cambios añadido.</li>
        <li>Solucionado error al contar las horas totales en un fichaje olvidado sin salida.</li>
        <li>Pequeños bugs solucionados.</li>
    </ul>

<div>

@stop
