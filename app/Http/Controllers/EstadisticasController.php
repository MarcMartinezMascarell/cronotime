<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DB;

use App\Models\Fichaje;
use App\Models\User;

class EstadisticasController extends Controller
{
    public function informe(Request $request) {
        $entrada = $request->get('start', Carbon::now()->startOfMonth());
        $salida = $request->get('end', Carbon::today());
        $userId = $request->get('userId');
        if($userId) {
            $user = User::find($userId);
            if($user && $user->company->id == Auth::user()->company->id) {
                if(!Auth::user()->hasAnyRole(['superAdmin', 'administrador']))
                    return redirect()->back()->withError('No tienes permiso para hacer eso');
                $user = $user;
            }
            else {
                if(Auth::user()->hasAnyRole(['superAdmin', 'administrador'])) {
                    $user = $user;
                } else {
                    return redirect()->back()->withError(__('Este usuario no pertenece a tu organización'));
                }
            }
        } else
            $user = Auth::user();

        if($user) {
            $fichajesPeriodo = Fichaje::where('user_id', $user->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
            ->orderBy('started_at')
            ->select('fichajes.*')->get();
            $fichajesOlvidadosPeriodo = Fichaje::where('user_id', $user->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
            ->orderBy('started_at')
            ->where('forgot', 1)
            ->select('fichajes.*')->get();
            $numero_olvidados = $fichajesOlvidadosPeriodo->count();
            $numero_fichajes = $fichajesPeriodo->count();
            $dias_trabajados = Fichaje::where('user_id', $user->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
            ->groupBy(DB::raw("DATE_FORMAT(started_at, '%d-%m-%Y')"))
            ->select(DB::raw("DATE_FORMAT(started_at, '%d-%m-%Y')"))
            ->get();
            $numero_dias_trabajados = $dias_trabajados->count();
            $total_minutes_periodo = Fichaje::where('user_id', $user->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
            ->sum('total_time');
            $total_periodo = $this->minutesToHours($total_minutes_periodo);
            $ultimoFichaje = Fichaje::where('user_id', $user->id)->orderBy('started_at', 'desc')->first();
            if($numero_dias_trabajados)
                $mediaHoras = $this->minutesToHours($total_minutes_periodo/$numero_dias_trabajados);
            else
                $mediaHoras = '';
            return view('pages.informe', [
                'entrada' => $entrada,
                'salida' => $salida,
                'fichajesPeriodo' => $fichajesPeriodo,
                'diasTrabajados' => $numero_dias_trabajados,
                'numeroFichajes' => $numero_fichajes,
                'totalPeriodo' => $total_periodo,
                'numeroOlvidados' => $numero_olvidados,
                'mediaHoras' => $mediaHoras,
                'userId' => $userId,
                'ultimoFichaje' => $ultimoFichaje,
            ]);
        } else {
            return redirect()->route('login');
        }
    }




    private function minutesToHours($total_minutes) {
        $hours = floor($total_minutes / 60);
        if($hours < 10)
            $hours = '0' . $hours;
        $minutes = $total_minutes % 60;
        if($minutes < 10)
            $minutes = '0' . $minutes;
        $total = $hours . ':' . $minutes;
        return $total;
    }
}



