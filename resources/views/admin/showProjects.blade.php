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
    <div class="card shadow mt--2">
            <div class="card-header bg-transparent border-0 d-flex flex-row justify-content-between">
            <h3 class=" mb-0">Proyectos</h3>
            <label class="d-flex gap-2 text-muted" for="showEndedProjects">Mostrar proyectos finalizados<input id="showEndedProjects" name="showEndedProjects" type="checkbox"></label>
            </div>
            <div class="table-responsive rounded">
                <table id="projectsTable" class="table align-items-center bg-white "
                data-toggle="table"
                data-search="false">
                <thead class="bg-light">
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
                                    <a class="btn btn-sm btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('project.edit', ['id' => $project->id]) }}">Editar</a>
                                        <form method="post" action="{{ route('project.inactive', ['id' => $project->id]) }}">
                                            @csrf
                                            @method('patch')
                                            <button type="submit" class="dropdown-item text-danger" href="#">{{__('Finalizar Proyecto')}}</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>

    <div class="endedProjects card shadow mt-4">
        <div class="card-header bg-transparent border-0">
        <h3 class=" mb-0">Proyectos finalizados</h3>
        </div>
        <div class="table-responsive rounded">
            <table id="endedProjectsTable" class="table align-items-center bg-white "
            data-toggle="table"
            data-search="false">
            <thead class="bg-light">
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
                    @foreach($endedProjects as $project)
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
                                <a class="btn btn-sm btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <form method="post" action="{{ route('project.update', ['id' => $project->id]) }}">
                                        @csrf
                                        @method('patch')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="dropdown-item text-success" href="#">{{__('Reactivar Proyecto')}}</button>
                                    </form>
                                    <form method="post" action="{{ route('project.delete', ['id' => $project->id]) }}">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="dropdown-item text-danger" href="#">{{__('Eliminar para siempre')}}</button>
                                    </form>
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
