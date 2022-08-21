@extends('layouts.app')

@section('content')

@include('layouts.headers.cards')

<div class="container-fluid mt--8">
    <div class="card d-flex justify-content-center py-3">
        <h2 class="text-center">¡Bienvenido {{Auth::user()->name}}!</h2>
        <div class="container mt-4 d-flex flex-column">
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col d-flex flex-column">
                                    <h5 class="text-muted mb-0 small text-center">Fichajes de hoy</h5>
                                </div>
                            </div>
                            <!-- FOREACH HORAS -->
                            @foreach($fichajesHoy as $fichaje)
                            <div class="row mt-2">
                                <div class="col d-flex align-items-center justify-content-between">
                                    <p class="sentido w-50 text-center m-0">Entrada</p>
                                    <span class="w-50 text-center">{{$fichaje->started_at->format('H:i')}}</span>
                                        <a href="#" class="d-flex align-items-center text-dec-none text-danger" data-toggle="modal" data-target="#delete-entrada"
                                        data-idfichaje="{{$fichaje->id}}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                </div>
                            </div>
                                @if($fichaje->stopped_at)
                                <div class="row mt-2">
                                    <div class="col d-flex align-center">
                                        <p class="sentido w-50 text-center m-0">Salida</p>
                                        <span class="w-50 text-center">{{$fichaje->stopped_at->format('H:i')}}</span>
                                        <a href="#" class="d-flex align-items-center text-dec-none text-danger" data-toggle="modal" data-target="#delete-salida"
                                        data-idFichaje="{{$fichaje->id}}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                @endif
                            @endforeach


                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col d-flex flex-column">
                                    <h5 class="text-muted mb-0 small text-center">Horas trabajadas hoy</h5>
                                    <span class="h4 font-weight-bold mb-0 mt-1 text-center">{{$total_hoy}}</span>
                                </div>
                            </div>
                            <p class="mb-0 mt-2 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2">Llevas un</span>
                                <span class="text-success text-center"><i class="fa fa-arrow-up"></i>
                                    <?php
                                    $porcentaje_dia = $total_minutes_hoy*100/480;
                                    echo round($porcentaje_dia, 2) . '%';
                                    ?>
                                </span>

                            </p>
                            <p class="mb-0 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2 w-100">de lo previsto para hoy</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col d-flex flex-column">
                                    <h5 class="text-muted mb-0 small text-center">Horas trabajadas esta semana</h5>
                                    <span class="h4 font-weight-bold mb-0 mt-1 text-center">{{$total_semana}}</span>
                                </div>
                            </div>
                            <p class="mb-0 mt-2 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2">Llevas un</span>
                                <span class="text-success text-center"><i class="fa fa-arrow-up"></i>
                                    <?php
                                    $porcentaje_semana = $total_minutes_semana*100/2400;
                                    echo round($porcentaje_semana, 2) . '%';
                                    ?>
                                </span>

                            </p>
                            <p class="mb-0 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2 w-100">de lo previsto esta semana</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col d-flex flex-column">
                                    <h5 class="text-muted mb-0 small text-center">Resumen semanal</h5>
                                </div>
                            </div>
                            <!-- FOREACH HORAS -->
                            <div class="row mt-2">
                                <div class="col d-flex align-center">
                                    <p class="sentido w-50 text-center m-0">Lunes</p>
                                    <span class="w-50 text-center">07:22</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col d-flex align-center">
                                    <p class="sentido w-50 text-center m-0">Martes</p>
                                    <span class="w-50 text-center">06:57</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row mb-4">
                <div class="col d-flex justify-content-center flex-column">
                        @if(isset($ultimoFichaje->stopped_at) || $ultimoFichaje == null)
                            <div class="container">
                            @if($ultimoFichaje)
                                <p class="text-muted text-center m-0">Estas fuera desde {{$ultimoFichaje->stopped_at->format('H:i (d/m/y)')}}</p>
                            @endif
                            </div>
                            <a href="{{ route('setFichaje') }}" class="btn btn-lg btn-success w-50 mx-auto mt-4">
                                ENTRAR
                            </a>
                        @else
                            <div class="container">
                                <p class="text-muted text-center m-0">Estas dentro desde las {{$ultimoFichaje->started_at->format('H:i')}}</p>
                            </div>
                            <a href="{{ route('setFichaje') }}" class="btn btn-lg btn-danger w-50 mx-auto mt-4">
                                SALIR
                            </a>
                        @endif


                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="delete-entrada" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">Atención</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="bold">Si el fichaje dispone de entrada y salida, su salida correspondiente será borrada también</p>
            </div>

            <div class="modal-footer">
                <form id="delete-fichaje-form" action="{{ route('fichaje.delete')}}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="idFichaje" value="">
                    <input type="hidden" name="type" value="entrada">
                    <button type="submit" class="btn btn-danger text-white">Borrar</button>
                </form>
                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="delete-salida" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">Atención</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="bold">El fichaje de salida será borrado</p>
            </div>

            <div class="modal-footer">
                <form id="delete-fichaje-form" action="{{ route('fichaje.delete')}}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="idFichaje" value="">
                    <input type="hidden" name="type" value="salida">
                    <button type="submit" class="btn btn-danger text-white">Borrar</button>
                </form>
                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>


@stop
