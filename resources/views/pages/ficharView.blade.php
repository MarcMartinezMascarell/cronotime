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
        <h2 class="text-center">{{__("¡Buenos días")}} {{Auth::user()->name}}!</h2>
        @if(Auth::user()->horas)
        <p class="text-muted text-center m-0">
            <?php
                $diaActual = Carbon\Carbon::now()->locale('en')->dayName;
                $horasTrabajador = Auth::user()->horas->get($diaActual)->first();
                $horasPrevistas = $horasTrabajador[$diaActual];
                echo $horasPrevistas;
            ?>
            {{__("horas previstas para hoy")}}.
        </p>
        @endif
        <div class="container mt-4 d-flex flex-column">
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col d-flex flex-column">
                                    <h5 class="text-muted mb-0 small text-center">{{__("Fichajes de hoy")}}</h5>
                                </div>
                            </div>
                            <!-- FOREACH HORAS -->
                            @foreach($fichajesHoy as $fichaje)
                            <div class="row mt-2">
                                <div class="col d-flex align-items-center justify-content-between">
                                    <p class="sentido w-50 text-center m-0">
                                        Entrada
                                        @if($fichaje->forgot == 1)
                                            <i class="text-muted fas fa-clock" data-toggle="tooltip" data-placement="top" title="{{__("Fichaje olvidado")}}"></i>
                                        @endif
                                        @isset($ultimoFichaje->stopped_at)
                                            @if($ultimoFichaje->stopped_at->gt($fichaje->started_at) && !$fichaje->stopped_at)
                                            <a href="" data-toggle="modal" data-target="#set-salida" data-idfichaje="{{$fichaje->id}}">
                                                <i class="text-warning fas fa-exclamation-triangle" data-toggle="tooltip" data-placement="top"
                                                title="{{__("Este fichaje no tiene salida. Haz click para añadirle una o eliminalo")}}."></i>
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
                                            {{__("Salida")}}
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
                                    <h5 class="text-muted mb-0 small text-center">{{__("Horas trabajadas hoy")}}</h5>
                                    <span class="h4 font-weight-bold mb-0 mt-1 text-center">{{$total_hoy}}</span>
                                </div>
                            </div>
                            @if(Auth::user()->horas)
                            <p class="mb-0 mt-2 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2">{{__("Llevas un")}}</span>
                                <span class="text-success text-center"><i class="fa fa-arrow-up"></i>
                                    <?php
                                    $diaActual = Carbon\Carbon::now()->locale('en')->dayName;
                                    $horasTrabajador = Auth::user()->horas->get($diaActual)->first();
                                    $horasPrevistas = $horasTrabajador[$diaActual];
                                    $minutosPrevistos = $horasPrevistas * 60;
                                    if(!$minutosPrevistos)
                                        $minutosPrevistos = 1;
                                    $porcentaje_dia = $total_minutes_hoy*100/$minutosPrevistos;
                                    echo round($porcentaje_dia, 2) . '%';
                                    ?>
                                </span>


                            </p>
                            <p class="mb-0 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2 w-100">{{__("de lo previsto para hoy")}}</span>
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
                                    <h5 class="text-muted mb-0 small text-center">{{__("Horas trabajadas esta semana")}}</h5>
                                    <span class="h4 font-weight-bold mb-0 mt-1 text-center">{{$total_semana}}</span>
                                </div>
                            </div>
                            @if($semanaPrevisto)
                            <p class="mb-0 mt-2 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2">{{__("Llevas un")}}</span>
                                <span class="text-success text-center"><i class="fa fa-arrow-up"></i>
                                    <?php
                                    $porcentaje_semana = $total_minutes_semana*100/($semanaPrevisto * 60);
                                    echo round($porcentaje_semana, 2) . '%';
                                    ?>
                                </span>

                            </p>
                            <p class="mb-0 text-muted text-sm d-flex justify-content-center">
                                <span class="text-nowrap text-center mr-2 w-100">{{__("de lo previsto esta semana")}}</span>
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
                                    <h5 class="text-muted mb-0 small text-center">{{__("Resumen semanal")}}</h5>
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
                                <p class="text-muted text-center m-0">{{__("Estas fuera desde")}} {{$ultimoFichaje->stopped_at->format('H:i (d/m/y)')}}</p>
                            @endif
                            </div>
                            <a href="{{ route('setFichaje') }}" class="btn btn-lg btn-success w-50 mx-auto mt-4">
                                {{__("ENTRAR")}}
                            </a>
                            <a href="" class="text-muted text-center w-auto mt-4" data-toggle="modal" data-target="#fichaje-olvidado">
                                {{__("He olvidado un fichaje")}}
                            </a>

                        @else
                            <div class="container">
                                <p class="text-muted text-center m-0">{{__("Estas dentro desde las")}} {{$ultimoFichaje->started_at->format('H:i')}}</p>
                            </div>
                            <a href="{{ route('setFichaje') }}" class="btn btn-lg btn-danger w-50 mx-auto mt-4">
                                {{__("SALIR")}}
                            </a>
                            <a href="" class="text-muted text-center w-auto mt-4" data-toggle="modal" data-target="#set-salida" data-idfichaje="{{$ultimoFichaje->id}}">
                                {{__("He olvidado salir")}}
                            </a>
                        @endif


                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.popups')


@stop
