@extends('layouts.app')

@section('content')

@include('users.partials.header', [
    'title' => 'Changelog',
    'description' => __('A continuación encontrarás una lista detallada de todos los cambios realizados en la aplicación'),
])

<div class="container-fluid mt-4">
    {{-- <ul >
        <strong class="h3 mb-3">v1.1.0</strong>
        <small>(5/12/2022)</small>
        <li>Añadida nueva funcionalidad de Calendario Laboral. En ella podrás ver los distintos eventos creados por los administradores de tu empresa, así como añadir tus ausencias o solicitar vacaciones.</li>
    </ul> --}}
    <ul>
        <strong class="h5 mb-3">v1.0.3</strong>
        <small>(10/03/2023)</small>
        <li>Nueva funcionalidad: Proyectos, en la que podrás pedirle a tus trabajadores que asignen las horas que han realizado a una serie de proyectos creados. Posibilidad de activar la versión beta de la funcionalidad en el perfil del usuario administrador.</li>
        <li>Pequeños bugs solucionados.</li>
    </ul>
    <ul >
        <strong class="h5 mb-3">v1.0.2</strong>
        <small>(29/12/2022)</small>
        <li>Desarrollo empezado de nueva funcionalidad: Calendario Laboral.</li>
        <li>Añadida nueva información en tu informe: Horas totales por día y semana.</li>
        <li>Pequeños bugs solucionados.</li>
    </ul>
    <ul >
        <strong class="h5 mb-3">v1.0.1</strong>
        <small>(5/12/2022)</small>
        <li>Contador de <em>horas trabajadas hoy</em> y <em>horas trabajadas esta semana</em> ahora se actualiza automáticamente, sin necesidad de recargar la págna.</li>
        <li>Añadidos botones enlaces directos a "Semana anterior" y "Mes anterior" en el apartado de informes.</li>
        <li>Aumentado el tiempo de sesión a 7 días.</li>
        <li>Registro de cambios añadido.</li>
        <li>Solucionado error al contar las horas totales en un fichaje olvidado sin salida.</li>
        <li>Pequeños bugs solucionados.</li>
    </ul>

<div>

@stop
