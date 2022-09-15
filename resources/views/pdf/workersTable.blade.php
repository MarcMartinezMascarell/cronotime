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


<table>
                <thead>
                  <tr>
                    <th><b>{{__("Nombre y Apellidos")}}</b></th>
                    <th >{{__("Email")}}</th>
                    @hasrole('superAdmin')
                    <th>{{__("Administrador")}}</th>
                    @endhasrole
                    <th>{{__("Horas periodo")}}</th>
                    <th>{{__("Media diaria")}}</th>
                    <th>{{__("% Olvidados")}}</th>
                    @hasrole('superAdmin')
                    <th>{{__("Empresa")}}</th>
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
                    </tr>
                    @endforeach
    </tbody>
</table>
