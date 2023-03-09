@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => $project->name,
        'description' => __('A continuación encontrarás los detalles del proyecto en el periodo seleccionado.'),
    ])

<?php
function toHoursAndMinutes($totalMinutes) {
    $hours = floor($totalMinutes / 60);
    if($hours < 10)
        $hours = '0' . $hours;
    $minutes = $totalMinutes % 60;
    if($minutes < 10)
        $minutes = '0' . $minutes;
    $total = $hours . ':' . $minutes;
    return $total;
}

?>

<div class="container-fluid mt--4">
    <div class="card d-flex justify-content-center py-3 px-2">
        <div class="row px-4 py-2">

            <form id="informeForm" action="{{ route('project.show', ['id' => $project->id])}}" method="GET">
                <p class="text-muted m-0 mt-2">{{__("Cambiar período")}}</p>
                <div class="date-options my-2">
                    <a href="{{ route('project.show', [ 'id' => $project->id, 'start' => Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'), 'end' => Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')]) }}" class="btn btn-dark fecha_informe">{{__("Este mes")}}</a>
                    <a href="{{ route('project.show', [ 'id' => $project->id, 'start' => Carbon\Carbon::now()->subDays(7)->startOfWeek()->format('Y-m-d'), 'end' => Carbon\Carbon::now()->subDays(7)->endOfWeek()->format('Y-m-d')]) }}" class="btn btn-dark fecha_informe">{{__("Semana pasada")}}</a>
                    <a href="{{ route('project.show', [ 'id' => $project->id, 'start' => Carbon\Carbon::now()->subDays(30)->startOfMonth()->format('Y-m-d'), 'end' => Carbon\Carbon::now()->subDays(30)->endOfMonth()->format('Y-m-d')]) }}" class="btn btn-dark fecha_informe">{{__("Mes pasado")}}</a>
                </div>
                <div class="input-daterange datepicker row align-items-start">
                    <div class="col">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input name="start" class="form-control" type="date" value="{{ Carbon\Carbon::parse($entrada)->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input name="end" class="form-control" type="date" value="{{ Carbon\Carbon::parse($salida)->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <input class="btn btn-default" type="submit" value="Buscar">
            </form>
        </div>
        <div class="row px-4">

            <div class="col-sm-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col d-flex flex-column">
                                <h5 class=" mb-0 text-center">{{__("Tiempo total")}}</h5>
                                <p class="mb-0 text-center">{{ toHoursAndMinutes($project->totalTime($entrada, $salida)) }}</p>
                            </div>
                            <div class="col d-flex flex-column">
                                <h5 class=" mb-0 text-center">{{__("Máximo aportador")}}</h5>
                                <p class="mb-0 text-center">
                                    {{ ($project->topUser($entrada, $salida)) ?
                                        $project->topUser($entrada, $salida)->name . ' ' .$project->topUser($entrada, $salida)->surname : '' }}
                                </p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col d-flex flex-column">
                                <h5 class="mb-0 text-center">{{__("Estadísticas")}}</h5>
                            </div>
                        </div>
                        <div class="row mt-3">

                            <div class="col-lg-4 col-sm-4 col-md-12 text-center mb-2">
                                <h5 class="mb-0 text-center">{{__("Contribuciones")}}</h5>
                                @foreach ($project->contributors($entrada, $salida) as $contributor)
                                <div class="row mt-2">
                                    <div class="col">{{$contributor->name}} {{$contributor->surname}}</div>
                                    <div class="col">{{toHoursAndMinutes($contributor->total_time)}}</div>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-lg-4 col-sm-4 col-md-12 text-center mb-2">
                                <h5 class="mb-0 text-center">{{__("Participantes")}}</h5>
                                <p>{{$project->numberContributors($entrada, $salida)}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
