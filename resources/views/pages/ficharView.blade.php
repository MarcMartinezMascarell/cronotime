@extends('layouts.app')

@section('content')

@include('layouts.headers.cards')

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

<div class="container-fluid mt--8">
    <div class="card d-flex justify-content-center py-3">
        <h2 class="text-center">¡Buenos días {{Auth::user()->name}}!</h2>
        @if(Auth::user()->horas)
        <p class="text-muted text-center m-0">
            <?php
                $diaActual = Carbon\Carbon::now()->dayName;
                $horasTrabajador = Auth::user()->horas->get($diaActual)->first();
                $horasPrevistas = $horasTrabajador[$diaActual];
                echo $horasPrevistas;
            ?>
            horas previstas para hoy.
        </p>
        @endif
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
                                    <p class="sentido w-50 text-center m-0">
                                        Entrada
                                        @if($fichaje->forgot == 1)
                                            <i class="text-muted fas fa-clock" data-toggle="tooltip" data-placement="top" title="Fichaje olvidado"></i>
                                        @endif
                                        @isset($ultimoFichaje->stopped_at)
                                            @if($ultimoFichaje->stopped_at->gt($fichaje->started_at) && !$fichaje->stopped_at)
                                            <a href="" data-toggle="modal" data-target="#set-salida" data-idfichaje="{{$fichaje->id}}">
                                                <i class="text-warning fas fa-exclamation-triangle" data-toggle="tooltip" data-placement="top"
                                                title="Este fichaje no tiene salida. Haz click para añadirle una o eliminalo."></i>
                                            </a>
                                            @endif
                                        @endisset
                                    </p>
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
                                        <p class="sentido w-50 text-center m-0">
                                            Salida
                                            {{-- @if($fichaje->forgot == 1)
                                            <i class="text-muted fas fa-clock" data-toggle="tooltip" data-placement="top" title="Fichaje olvidado"></i>
                                            @endif --}}
                                        </p>

                                        <span class="w-50 text-center">{{$fichaje->stopped_at->format('H:i')}}</span>
                                        <a href="#" class="d-flex align-items-center text-dec-none text-danger" data-toggle="modal" data-target="#delete-salida"
                                        data-idFichaje="{{$fichaje->id}}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                <hr class="my-2">
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
                            @if(Auth::user()->horas)
                            <p class="mb-0 mt-2 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2">Llevas un</span>
                                <span class="text-success text-center"><i class="fa fa-arrow-up"></i>
                                    <?php
                                    $diaActual = Carbon\Carbon::now()->dayName;
                                    $horasTrabajador = Auth::user()->horas->get($diaActual)->first();
                                    $horasPrevistas = $horasTrabajador[$diaActual];
                                    $minutosPrevistos = $horasPrevistas * 60;
                                    $porcentaje_dia = $total_minutes_hoy*100/$minutosPrevistos;
                                    echo round($porcentaje_dia, 2) . '%';
                                    ?>
                                </span>


                            </p>
                            <p class="mb-0 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2 w-100">de lo previsto para hoy</span>
                            </p>
                            @endif
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
                            @if($semanaPrevisto)
                            <p class="mb-0 mt-2 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2">Llevas un</span>
                                <span class="text-success text-center"><i class="fa fa-arrow-up"></i>
                                    <?php
                                    $porcentaje_semana = $total_minutes_semana*100/($semanaPrevisto * 60);
                                    echo round($porcentaje_semana, 2) . '%';
                                    ?>
                                </span>

                            </p>
                            <p class="mb-0 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2 w-100">de lo previsto esta semana</span>
                            </p>
                            @endif
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
                            @foreach($minutes_per_day as $minutes)
                                <div class="row mt-2">
                                    <div class="col d-flex align-center align-items-center">
                                        <p class="h6 sentido w-50 text-center m-0">{{$minutes->started_at->format('d/m/Y')}}</p>
                                        <?php
                                                    $minutes_of_day = $minutes->total_time;
                                                    $total = toHoursAndMinutes($minutes_of_day);
                                        ?>
                                        <span class="w-50 text-center">{{$total}}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row mb-4">
                <div class="col d-flex justify-content-center flex-column">
                        @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="fas fa-thumbs-down"></i></span>
                            <span class="alert-text"><strong>Cuidado!</strong> {{Session::get('error')}}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        @if(isset($ultimoFichaje->stopped_at) || $ultimoFichaje == null)
                            <div class="container">
                            @if($ultimoFichaje)
                                <p class="text-muted text-center m-0">Estas fuera desde {{$ultimoFichaje->stopped_at->format('H:i (d/m/y)')}}</p>
                            @endif
                            </div>
                            <a href="{{ route('setFichaje') }}" class="btn btn-lg btn-success w-50 mx-auto mt-4">
                                ENTRAR
                            </a>
                            <a href="" class="text-muted text-center w-auto mt-4" data-toggle="modal" data-target="#fichaje-olvidado">
                                He olvidado un fichaje
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

<div class="modal fade" id="fichaje-olvidado" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">Has olvidado un fichaje?</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="fichajeOlvidadoForm" action="{{ route('fichaje.olvidado')}}" method="POST">
                @csrf
                @method('POST')
            <div class="modal-body">
                <div class="input-daterange datepicker row align-items-start">
                    <div class="col">
                        <div class="form-group m-0">
                            <p class="h5 text-center">Entrada</p>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                @if($ultimoFichaje)
                                <input name="entrada" class="form-control" type="datetime-local" value="{{ $ultimoFichaje->started_at->format('Y-m-d H:i:00') }}">
                                @else
                                <input name="entrada" class="form-control" type="datetime-local" value="{{ \Carbon\Carbon::now()->format('Y-m-d H:i:00') }}">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group m-0">
                            <p class="h5 text-center">Salida</p>
                            <div class="collapse" id="exit">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input name="salida" class="form-control" type="datetime-local" value="{{ \Carbon\Carbon::now()->format('Y-m-d H:i:00') }}">
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <label class="custom-toggle mt-2 d-flex" href="#exit" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples">
                                    <input name="salida_yes" type="checkbox" value="true">
                                    <span class="custom-toggle-slider" data-label-off="Aún no he salido" data-label-on="Yes"></span>

                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex small align-items-center mt-2">
                        <i class="text-muted fas fa-clock mx-2"></i>
                        <p class="text-muted m-0">Quedará registrado como fichaje olvidado</p>
                    </div>


                </div>
            </div>

            <div class="modal-footer">
                    <button type="submit" class="btn btn-default">Crear fichaje olvidado</button>

                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Cancelar</button>
            </div>
        </form>

        </div>
    </div>
</div>

<div class="modal fade" id="set-salida" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">Añadir salida al fichaje olvidado</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="setSalidaForm" action="{{ route('fichaje.salida')}}" method="POST">
                @csrf
                @method('POST')
            <div class="modal-body">
                <div class="input-daterange datepicker row align-items-start">
                    <div class="col">
                        <div class="form-group m-0">
                            <p class="h5 text-center">Salida</p>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input type="hidden" name="idFichaje" value="">
                                    <input name="salida" class="form-control" type="datetime-local" value="{{ \Carbon\Carbon::now()->format('Y-m-d H:i:00') }}">
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="modal-footer">
                    <button type="submit" class="btn btn-default">Crear salida</button>

                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Cancelar</button>
            </div>
        </form>

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
