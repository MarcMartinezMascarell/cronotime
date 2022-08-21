@extends('layouts.app')

@section('content')

@include('users.partials.header', [
        'title' => __('Hola') . ' '. auth()->user()->name,
    ]) 

<div class="table-responsive">
    <table id="companiesTable" class="table align-items-center table-dark"
    data-toggle="table"
    data-search="false">
    <thead class="thead-dark">
        <tr>
            <th scope="col" data-sortable="true">Empresa</th>
            <th scope="col" data-sortable="true" >Empleados / Límite</th>
            <th scope="col">Administrador</th>
            <th scope="col">Users</th>
            <th scope="col">Completion</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($empresas as $empresa)
            <tr>
                <th scope="row">
                    <div class="media align-items-center">
                        <a href="#" class="avatar rounded-circle mr-3">
                        <img alt="Image placeholder" src="../../assets/img/theme/bootstrap.jpg">
                        </a>
                        <div class="media-body">
                            <span class="mb-0 text-sm">{{ $empresa->nombre }}</span>
                        </div>
                    </div>
                </th>
                <td>
                    <div class="text-sm">
                        {{ $empresa->workersCount() }} / {{ $empresa->workers_limit }}
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

                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="mr-2">60%</span>
                        <div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                            </div>
                        </div>
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

@stop