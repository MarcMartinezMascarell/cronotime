<!DOCTYPE html>
<html>
  <head>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Informe PDF</title>
    </head>
  </head>
  <style>
    body {
        font-family: DejaVu Sans, sans-serif;
    }
    .container {
        max-width: 1200px;
        margin: 0px auto;
        padding: 0rem 4rem
    }
    p {
        margin: 0;
    }
    .title {
        margin: 0;
    }
    .subtitle {
        color: grey;
        margin: 0;
    }
    table {
        margin: 2rem auto;
    }
    th {
        padding: 1rem 2rem;
        text-align: center;
        font-size: 20px
    }
    td {
        padding: 0 2rem;
        text-align: center;
        font-size: 24px;
    }
    .red {
        color: red;
    }
    .yellow {
        color: yellow;
    }
    .green {
        color: green;
    }
    .muted {
        color: #6c757d;
        font-weight: 300;
        font-size: 16px;
    }
    ul {
        list-style: none;
        padding: 0;
    }
    li {

        padding: 0.5rem 0;
    }
    .horas, .totalHoras {
        display: inline-block;
    }
    header {
                top: 0px;
                left: 0px;
                right: 0px;
                font-size: 20px;
                max-width: 1200px;
                margin: 10px auto;
    }
  </style>
  <body>

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

    <header>
        <div class="pdf_logo" style="width: 100%;margin-bottom:30px;border-bottom:1px solid grey">
            @if($user->company->logo_url)
            <img alt="Logo" src="{{storage_path('app/public/images/logos/'.auth()->user()->company->logo_url)}}" style="width:100px; max-height:100px; object-fit:contain;">
            {{-- <img alt="Logo" src="{{asset('storage/images/logos/'.auth()->user()->company->logo_url)}}" style="width:100px; max-height:100px; object-fit:contain;"> --}}
            @endif
            <p class="subtitle">{{$user->company->nombre}}</p>
        </div>
    </header>

    <div class="container">
        <div class="title-container">
            <h1 class="title">Informe {{$user->name}} {{$user->surname}}</h1>
            <p class="subtitle">{{Carbon\Carbon::parse($entrada)->format('d/m/y')}} - {{Carbon\Carbon::parse($salida)->format('d/m/y')}}</p>
        </div>
        <table>
            <thead>
                <tr>
                  <th><b>{{__("Horas totales")}}</b></th>
                  <th><b>{{__("Media diaria")}}</b></th>
                  <th><b>{{__("% Olvidados")}}</b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                    <p><strong>{{$totalPeriodo}}h</strong></p>
                    </td>
                    <td>
                    <p><strong>{{$mediaHoras}}h</strong></p>
                    </td>
                    <td>
                        <?php
                        $porcentajeOlvidados = 0;
                        if($numeroOlvidados) {
                            $porcentajeOlvidados = $numeroOlvidados*100/$numeroFichajes;
                        }
                        if($porcentajeOlvidados >= 20)
                            $colorOlvidados = 'red';
                        else if ($porcentajeOlvidados >= 10)
                            $colorOlvidados = 'yellow';
                        else
                            $colorOlvidados = 'green';
                        ?>
                        <p class="<?php echo $colorOlvidados ?>">
                            <strong>
                                {{round($porcentajeOlvidados, 2)}}%
                            </strong>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                  <td class="muted">{{__("Número de días trabajados")}}</td>
                  <td class="muted">{{__("Total de fichajes")}}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="muted"><b>{{$diasTrabajados}}</b></td>
                    <td class="muted"><b>{{$numeroFichajes}}</b></td>
                </tr>
            </tbody>
        </table>
        <div class="fichajes-container">
            <h2 class="title-fichajes">{{__("Todos los fichajes")}}</h2>
            <ul>
                <?php
                $previousDate = null;
                ?>
                @foreach($fichajesPeriodo as $fichaje)
                <?php
                    if($previousDate != Carbon\Carbon::parse($fichaje->started_at)->format('d/m/Y')) {
                        echo '<p style="border-bottom: 1px solid #6c757d;font-weight:bold;">' . Carbon\Carbon::parse($fichaje->started_at)->format('d/m/Y') . '</p>';
                        $previousDate = Carbon\Carbon::parse($fichaje->started_at)->format('d/m/Y');
                    }
                ?>
                    <li>
                        <div class="horas">
                            <div class="entrada">
                                <p class="title-fichaje">Entrada | {{Carbon\Carbon::parse($fichaje->started_at)->format('h:i')}}
                                @if($fichaje->forgot == 1)
                                &#9888;
                                @endif
                                </p>
                            </div>
                            @isset($fichaje->stopped_at)
                            <div class="salida">
                                <?php
                                if($fichaje->started_at->format('d-m-Y') != $fichaje->stopped_at->format('d-m-Y'))
                                    $fecha = $fichaje->stopped_at->format('d/m/Y');
                                ?>
                                <p class="title-fichaje">{{__("Salida")}} | {{Carbon\Carbon::parse($fichaje->stopped_at)->format('h:i')}}
                                    @isset ($fecha)
                                    ({{$fecha}})
                                    @endisset
                                </p>
                            </div>
                            @endisset
                        </div>
                        <div style="margin-left:3rem;" class="totalHoras">
                            <p>Total:</p>
                            <p>{{toHoursAndMinutes($fichaje->total_time)}}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>


    </div>
  </body>
</html>
