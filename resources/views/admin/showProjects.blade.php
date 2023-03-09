@extends('layouts.app')

@section('content')

@include('users.partials.header', [
        'title' => __('Hola') . ' '. auth()->user()->name,
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

<div class="container-fluid">
    <div class="card bg-default shadow">
            <div class="card-header bg-transparent border-0">
            <h3 class="text-white mb-0">Proyectos</h3>
            </div>
            <div class="table-responsive">
                <table id="companiesTable" class="table align-items-center table-dark table-flush"
                data-toggle="table"
                data-search="false">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" data-sortable="true" >Proyecto</th>
                        <th scope="col" data-sortable="true" >Horas dedicadas este mes</th>
                        <th scope="col" data-sortable="true" >Máximo aportador</th>
                        <th scope="col" data-sortable="true" >Actualizado el</th>
                        <th scope="col" data-sortable="true" >Creado el</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                        @foreach($projects as $project)
                        <tr>
                            <th scope="row">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <a href="{{route('project.show', ['id' => $project->id])}}">
                                            <span class="mb-0 text-sm">{{$project->name}}</span>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <td>
                                <div class="text-sm font-weight-bold">
                                    {{toHoursAndMinutes($project->totalTime(Carbon\Carbon::now()->startOfMonth(), Carbon\Carbon::now()))}}
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">

                                    {{ ($project->topUser(Carbon\Carbon::now()->startOfMonth(), Carbon\Carbon::now())) ?
                                    $project->topUser(Carbon\Carbon::now()->startOfMonth(), Carbon\Carbon::now())->name . ' ' .$project->topUser(Carbon\Carbon::now()->startOfMonth(), Carbon\Carbon::now())->surname : '' }}
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    {{$project->updated_at->format('d/m/y')}}
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    {{$project->created_at->format('d/m/y')}}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="#">Editar</a>
                                        <a class="dropdown-item text-danger" href="#">Eliminar proyecto</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
    </div>
</div>

@stop
