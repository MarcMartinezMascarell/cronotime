@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Hola') . ' '. auth()->user()->name,
        'description' => __('A continuación encontrarás tu informe, puedes elegir el período que más te convenga'),
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
        {{-- @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-icon"><i class="fas fa-thumbs-down"></i></span>
            <span class="alert-text"><strong>{{__("¡Cuidado!")}}</strong> {{Session::get('error')}}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif --}}
        <div class="row px-4 py-2">

            <form id="informeForm" action="{{ route('estadisticas.informe')}}" method="GET">
                @hasanyrole('superAdmin|administrador')
                <p class="text-muted m-0">{{__("Seleccionar trabajador")}}</p>
                <div class="row col-12 col-lg-6">
                    <div class="form-group m-0 p-0">
                        <select class="form-control" name="userId">
                            @foreach (Auth::user()->company->workers as $worker)
                                <option <?php echo $worker->id == $userId ? 'selected' : '' ?> value="{{$worker->id}}">{{$worker->name}} {{$worker->surname}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endhasanyrole
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
                <input class="btn btn-default" type="submit" value="Buscar">
            </form>
        </div>
        <div class="row px-4">
            @if(!$numeroFichajes)
            <p class="text-center text-muted">{{__("No hay datos entre estas fechas")}}</p>
            @endif
            <?php
                if(!$userId)
                    $userId = Auth::id();
            ?>
            <form id="downloadPDFForm" class="d-flex justify-content-end" action="{{ route('pdf.download')}}" method="get">
                <input type="hidden" name="userId" value="{{$userId}}">
                <input type="hidden" name="start" value="{{ Carbon\Carbon::parse($entrada)->format('Y-m-d') }}">
                <input type="hidden" name="end" value="{{ Carbon\Carbon::parse($salida)->format('Y-m-d') }}">
                <a class="text-center text-muted" href="javascript:$('#downloadPDFForm').submit();"><i class="fas fa-file-download"></i></a>
            </form>

            <div class="col-sm-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col d-flex flex-column">
                                <h5 class=" mb-0 text-center">{{__("Fichajes")}}</h5>
                            </div>
                        </div>
                        <!-- FOREACH HORAS -->
                        <?php
                            $previousDate = null;
                            $fichajeCounter = 0;
                        ?>
                        @foreach($fichajesPeriodo as $fichaje)
                        <?php
                            if($previousDate != Carbon\Carbon::parse($fichaje->started_at)->format('d/m/Y')) {
                                echo '<p class="m-0 text-muted small">' . Carbon\Carbon::parse($fichaje->started_at)->format('d/m/Y') . '</p>';
                                $previousDate = Carbon\Carbon::parse($fichaje->started_at)->format('d/m/Y');
                            }
                        ?>
                        <div class="row mt-1">
                            <div class="col d-flex align-items-center justify-content-between">
                                <p class="sentido w-50 text-center m-0 small">
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
                                <span class="w-50 text-center small">{{$fichaje->started_at->format('H:i')}}</span>
                                <a href="#" class="d-flex align-items-center text-dec-none text-danger" data-toggle="modal" data-target="#delete-entrada"
                                data-idfichaje="{{$fichaje->id}}">
                                    <i class="fas fa-trash"></i>
                                </a>
                                    {{-- <a href="#" class="d-flex align-items-center text-dec-none text-danger small" data-toggle="modal" data-target="#delete-entrada"
                                    data-idfichaje="{{$fichaje->id}}">
                                        <i class="fas fa-trash"></i>
                                    </a> --}}
                            </div>
                        </div>
                        @if($fichaje->stopped_at)
                            <div class="row mt-1">
                                <div class="col d-flex align-center">
                                    <p class="sentido w-50 text-center m-0 small">
                                        {{__("Salida")}}
                                    </p>

                                    <?php
                                        if($fichaje->started_at->format('d-m-Y') != $fichaje->stopped_at->format('d-m-Y'))
                                            $fecha = $fichaje->stopped_at->format('d/m/Y');
                                    ?>
                                    <span class="w-50 text-center small">{{$fichaje->stopped_at->format('H:i')}}
                                    @isset ($fecha)
                                        ({{$fecha}})
                                    @endisset
                                    </span>
                                    <a href="#" class="d-flex align-items-center text-dec-none text-danger" data-toggle="modal" data-target="#delete-salida"
                                    data-idfichaje="{{$fichaje->id}}">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {{-- <a href="#" class="d-flex align-items-center text-dec-none text-danger" data-toggle="modal" data-target="#delete-salida"
                                    data-idFichaje="{{$fichaje->id}}">
                                        <i class="fas fa-trash"></i>
                                    </a> --}}
                                </div>
                            </div>
                            @endif
                            <?php
                                $fichajeCounter += 1;
                                if($fichajeCounter > 10) {
                                    ?>
                                        <form id="downloadPDFForm2" class="d-flex justify-content-center" action="{{ route('pdf.download')}}" method="get">
                                            <input type="hidden" name="userId" value="{{$userId}}">
                                            <input type="hidden" name="start" value="{{ Carbon\Carbon::parse($entrada)->format('Y-m-d') }}">
                                            <input type="hidden" name="end" value="{{ Carbon\Carbon::parse($salida)->format('Y-m-d') }}">
                                            <a class="text-center text-muted" href="javascript:$('#downloadPDFForm2').submit();">{{__("Descárgate el pdf para verlos todos...")}}<i class="fas fa-file-download ml-2"></i></a>
                                        </form>
                                    <?php
                                    break;
                                }
                            ?>
                            <hr class="m-1">
                        @endforeach


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
                                <h4 class="m-0">{{__("Horas totales")}}</h4>
                                <h3 class="m-0 bold"><strong>{{$totalPeriodo}}</strong></h3>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-md-12 text-center mb-2">
                                <h4 class="m-0">{{__("% Olvidados")}}</h4>
                                <?php
                                $porcentajeOlvidados = 0;
                                if($numeroOlvidados) {
                                    $porcentajeOlvidados = $numeroOlvidados*100/$numeroFichajes;
                                }
                                if($porcentajeOlvidados >= 20)
                                    $colorOlvidados = 'danger';
                                else if ($porcentajeOlvidados >= 10)
                                    $colorOlvidados = 'warning';
                                else
                                    $colorOlvidados = 'success';
                                ?>
                                <h3 class="m-0 text-<?php echo $colorOlvidados ?>">
                                    <strong>
                                        {{round($porcentajeOlvidados, 2)}}%
                                    </strong>
                                </h3>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-md-12 text-center mb-2">
                                <h4 class="m-0">{{__("Media diaria")}}</h4>
                                <h3 class="m-0 "><strong>{{$mediaHoras}}</strong></h3>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-6 col-sm-4 col-md-12 text-center d-flex justify-content-center">
                                <h4 class="mr-2 small text-muted">{{__("Número de días trabajados")}}: </h4>
                                <h3 class="m-0 bold small text-muted"><strong>{{$diasTrabajados}}</strong></h3>
                            </div>
                            <div class="col-lg-6 col-sm-4 col-md-12 text-center d-flex justify-content-center">
                                <h4 class="mr-2 small text-muted">{{__("Número total de fichajes")}}: </h4>
                                <h3 class="m-0 bold small text-muted"><strong>{{$numeroFichajes}}</strong></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
