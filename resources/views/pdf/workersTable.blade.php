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
<style>
    th {
        font-weight: 900;
    }
</style>

<table>
                <thead>
                  <tr>
                    <th><b>{{__("Nombre y Apellidos")}}</b></th>
                    <th><b>{{__("Email")}}</b></th>
                    @hasrole('superAdmin')
                    <th><b>{{__("Administrador")}}</b></th>
                    @endhasrole
                    <th><b>{{__("Horas periodo")}}</b></th>
                    <th><b>{{__("Media diaria")}}</b></th>
                    <th><b>{{__("% Olvidados")}}</b></th>
                    <th><b>{{__("Número de días trabajados:")}}</b></th>
                    <th><b>{{__("Número total de fichajes:")}}</b></th>
                    @hasrole('superAdmin')
                    <th><b>{{__("Empresa")}}</b></th>
                    @endhasrole
                  </tr>
                </thead>
                <tbody>
                    @foreach($workers as $worker)
                    <tr>
                        <td>
                            {{ $worker->name }} {{ $worker->surname }}
                        </td>
                        <td>
                            {{ $worker->email }}
                        </td>
                        @hasrole('superAdmin')
                        <td>
                            {{ $empresa->administrador()->name }}
                        </td>
                        <td>
                            @if ($worker->company)
                            {{ $worker->company->nombre }}
                            @endif
                        </td>
                        @endhasrole

                        <td>
                            <?php echo toHoursAndMinutes($worker->fichajesBetween($entrada, $salida)->sum('total_time')) ?>
                        </td>
                        <td>
                            <?php echo toHoursAndMinutes($worker->mediaDiaria($entrada, $salida)) ?>
                        </td>
                        <td>
                            <?php echo round($worker->porcentajeOlvidados($entrada, $salida), 2) ?>%
                        </td>
                        <td>
                            <?php echo $worker->diasTrabajados($entrada, $salida) ?>
                        </td>
                        <td>
                            <?php echo $worker->numeroFichajes($entrada, $salida) ?>
                        </td>
                    </tr>
                    @endforeach
    </tbody>
</table>
