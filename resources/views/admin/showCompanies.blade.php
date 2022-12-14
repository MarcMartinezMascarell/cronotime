@extends('layouts.app')

@section('content')

@include('users.partials.header', [
        'title' => __('Hola') . ' '. auth()->user()->name,
    ])

<div class="container-fluid">
    <div class="card bg-default shadow">
            <div class="card-header bg-transparent border-0">
            <h3 class="text-white mb-0">Empresas</h3>
            </div>
            <div class="table-responsive">
                <table id="companiesTable" class="table align-items-center table-dark table-flush"
                data-toggle="table"
                data-search="false">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" data-sortable="true">Empresa</th>
                        <th scope="col" data-sortable="true" >Empleados / Límite</th>
                        <th scope="col" data-sortable="true" >Administrador</th>
                        <th scope="col" data-sortable="true" >Creado el</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                        @foreach($empresas as $empresa)
                        <tr>
                            <th scope="row">
                                <div class="media align-items-center">
                                    <div class="avatar rounded-circle mr-3">
                                        @if($empresa->logo_url)
                                        <img alt="Logo" src="{{asset('images/logos/'.$empresa->logo_url)}}">
                                        @else
                                        <img alt="Image placeholder" src="https://www.business2community.com/wp-content/uploads/2017/08/blank-profile-picture-973460_640.png">
                                        @endif
                                    </div>
                                    <div class="media-body">
                                        <span class="mb-0 text-sm">{{ $empresa->nombre }}</span>
                                    </div>
                                </div>
                            </th>
                            <td>
                                <div class="text-sm font-weight-bold">
                                    {{ $empresa->workersCount() }} / @if($empresa->workers_limit < 0) <i class="fas fa-infinity"></i> @else {{$empresa->workers_limit}} @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    @if($empresa->administrador())
                                    {{ $empresa->administrador()->email }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    @isset($empresa->created_at)
                                        {{ $empresa->created_at->format('d/m/Y') }}
                                    @endisset
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item text-danger" href="{{ route('company.delete', ['id' => $empresa->id]) }}">Eliminar empresa</a>
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
