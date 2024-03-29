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

<div class="container-fluid mt--5">
    <div class="card shadow p-3">
        <form id="informeForm" action="{{ route('workers.show', [$empresa->id])}}" method="GET" >
            <p class="text-muted m-0">{{__("Cambiar período")}}</p>
            <div class="input-daterange datepicker row align-items-start justify-content-center">
                <div class="col-12 col-lg-6 mb-2">
                    <div class="form-group m-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                            </div>
                            <input name="start" class="form-control" type="date" value="{{ Carbon\Carbon::parse($entrada)->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 mb-2">
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
            <input class="btn btn-primary" type="submit" value="Buscar">
        </form>
        {{-- <div class="card-header bg-transparent border-0">
            <h3 class="mb-0">Trabajadores</h3>
        </div> --}}
        <form id="downloadExcelForm" class="d-flex justify-content-end" action="{{ route('excel.download')}}" method="get">
            <input type="hidden" name="entrada" value="{{ Carbon\Carbon::parse($entrada)->format('Y-m-d') }}">
            <input type="hidden" name="salida" value="{{ Carbon\Carbon::parse($salida)->format('Y-m-d') }}">
            <input type="hidden" name="id" value="{{$empresa->id}}">
            <a class="text-center text-muted" href="javascript:$('#downloadExcelForm').submit();">Exportar Excel <i class="fas fa-file-download"></i></a>
        </form>
        <div class="table-responsive rounded mt-2">

            <table id="companiesTable" class="table align-middle mb-5 bg-white"
            data-toggle="table"
            data-search="false">
                <thead class="bg-light">
                  <tr>
                    <th scope="col" data-sortable="true">{{__("Nombre y Apellidos")}}</th>
                    <th scope="col" data-sortable="true" >{{__("Email")}}</th>
                    @hasrole('superAdmin')
                    <th scope="col" data-sortable="true">{{__("Administrador")}}</th>
                    @endhasrole
                    @hasrole('administrador')
                    <th scope="col" data-sortable="true">{{__("Rol")}}</th>
                    @endif
                    <th scope="col">{{__("Horas periodo")}}</th>
                    <th scope="col">{{__("Media diaria")}}</th>
                    <th scope="col">{{__("% Olvidados")}}</th>
                    @hasrole('superAdmin')
                    <th scope="col">{{__("Empresa")}}</th>
                    @endhasrole
                    @hasanyrole('administrador|superAdmin')
                    <th></th>
                    @endhasanyrole
                  </tr>
                </thead>
                <tbody>
                    @foreach($workers as $worker)
                    <tr>
                        <th scope="row">
                            <div class="media align-items-center">
                                <a href="#" class="avatar rounded-circle mr-3">
                                <img alt="Image placeholder" src="https://www.business2community.com/wp-content/uploads/2017/08/blank-profile-picture-973460_640.png">
                                </a>
                                <div class="media-body">
                                    <span class="mb-0 text-sm"><a href="{{route("estadisticas.informe", ['userId' => $worker->id])}}">{{ $worker->name }} {{ $worker->surname }}</a></span>
                                </div>
                            </div>
                        </th>
                        <td>
                            <div class="text-sm">
                                {{ $worker->email }}
                            </div>
                        </td>
                        @hasrole('administrador')
                        <td>
                            <div class="text-sm">
                                {{ $worker->getRoleNames()[0] }}
                            </div>
                        </td>
                        @endif
                        @hasrole('superAdmin')
                        <td>
                            <div class="text-sm">
                                @isset($empresa)
                                @if($empresa->administrador())
                                {{ $empresa->administrador()->name }}
                                @endif
                                @endisset
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                @if($worker->hasRole('administrador'))
                                <i class="fas fa-key"></i>
                                @elseif($worker->hasRole('superAdmin'))
                                <i class="fas fa-hammer"></i>
                                @endif
                            </div>
                        </td>
                        @endhasrole

                        <td>
                            <div class="text-sm">
                                <?php echo toHoursAndMinutes($worker->fichajesBetween($entrada, $salida)->sum('total_time')) ?>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                <?php echo toHoursAndMinutes($worker->mediaDiaria($entrada, $salida)) ?>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                <?php echo round($worker->porcentajeOlvidados($entrada, $salida), 2) ?>%
                            </div>
                        </td>
                        @hasrole('superAdmin')
                        <td>
                            <div class="text-sm">
                                @if ($worker->company)
                                {{ $worker->company->nombre }}
                                @endif
                            </div>
                        </td>
                        @endhasrole
                        @hasanyrole('administrador|superAdmin')
                        <td class="text-right">
                                @if($worker->id != auth()->user()->id)
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <form method="post" action="{{ route('workers.resetHours') }}">
                                        @csrf
                                        @method('patch')
                                            <input type="hidden" name="id" value="{{$worker->id}}">
                                            <button type="submit" class="dropdown-item" href="#">{{__('Reset Horas No usadas')}}</button>
                                        </form>
                                        <form method="post" action="{{ route('workers.toggleAdmin') }}">
                                        @csrf
                                        @method('patch')
                                            @if($worker->hasRole('administrador'))
                                            <input type="hidden" name="id" value="{{$worker->id}}">
                                            <button type="submit" class="dropdown-item text-danger" href="#">{{__('Eliminar administración')}}</button>
                                            @else
                                            <input type="hidden" name="id" value="{{$worker->id}}">
                                            <button type="submit" class="dropdown-item" href="#">{{__('Hacer administrador')}}</button>
                                            @endif
                                        </form>
                                        <form method="post" action="{{ route('workers.delete') }}">
                                        @csrf
                                        @method('delete')
                                            <input type="hidden" name="id" value="{{$worker->id}}">
                                            <button type="submit" class="dropdown-item text-danger" href="#">{{__('Eliminar Usuario')}}</button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            </td>
                        @endhasanyrole
                    </tr>
                    @endforeach
            </tbody>
            </table>


        </div>

    </div>
</div>


@stop
