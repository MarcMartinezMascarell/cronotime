@extends('layouts.app')

@section('content')

@include('users.partials.header', [
        'title' => __('Hola') . ' '. auth()->user()->name,
    ])

<div class="container-fluid mt--5">
    <div class="card shadow p-3">
        <form id="informeForm" action="{{ route('estadisticas.informe')}}" method="GET" >
            <p class="text-muted m-0">{{__("Cambiar período")}}</p>
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
            <input class="btn btn-primary" type="submit" value="Buscar">
        </form>
        {{-- <div class="card-header bg-transparent border-0">
            <h3 class="mb-0">Trabajadores</h3>
        </div> --}}
        <div class="table-responsive rounded mt-2">

            <table id="companiesTable" class="table align-middle mb-5 bg-white"
            data-toggle="table"
            data-search="false">
                <thead class="bg-light">
                  <tr>
                    <th scope="col" data-sortable="true">{{__("Nombre y Apellidos")}}</th>
                    <th scope="col" data-sortable="true" >{{__("Email")}}</th>
                    <th scope="col" data-sortable="true">{{__("Administrador")}}</th>
                    <th scope="col">{{__("Rol")}}</th>
                    <th scope="col">{{__("Horas menusales")}}</th>
                    <th scope="col">{{__("Media diaria")}}</th>
                    <th scope="col">{{__("% Olvidados")}}</th>
                    @hasrole('superAdmin')
                    <th scope="col">{{__("Empresa")}}</th>
                    @endhasrole
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
                        <td>
                            <div class="text-sm">
                                @hasrole('superAdmin')
                                    {{ $worker->company->nombre }}
                                @endhasrole
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </a>
                                {{-- <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item text-danger" href="{{ route('company.delete', ['id' => $empresa->id]) }}">Eliminar empresa</a>
                                </div> --}}
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
