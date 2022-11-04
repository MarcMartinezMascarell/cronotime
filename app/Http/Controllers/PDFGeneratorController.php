<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DB;
use PDF;
use Excel;
use App\Exports\workersTable;

use App\Models\Fichaje;
use App\Models\User;

class PDFGeneratorController extends Controller
{
    public function download(Request $request) {
        $id = $request->userId;
        $entrada = $request->start;
        $salida = $request->end;
        $user = User::find($id);
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
            if($numero_dias_trabajados)
                $mediaHoras = $this->minutesToHours($total_minutes_periodo/$numero_dias_trabajados);
            else
                $mediaHoras = '';

            $pdf = PDF::loadView('pdf.informe', [
                'entrada' => $entrada,
                'salida' => $salida,
                'fichajesPeriodo' => $fichajesPeriodo,
                'diasTrabajados' => $numero_dias_trabajados,
                'numeroFichajes' => $numero_fichajes,
                'totalPeriodo' => $total_periodo,
                'numeroOlvidados' => $numero_olvidados,
                'mediaHoras' => $mediaHoras,
                'user' => $user,
            ]);
            return $pdf->download($user->name . '.pdf');
        }

    }

    public function pdfTemplate() {
        $id = 3;
        $entrada = Carbon::now()->startOfMonth();
        $salida = Carbon::today();
        $user = User::find($id);
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
            if($numero_dias_trabajados)
                $mediaHoras = $this->minutesToHours($total_minutes_periodo/$numero_dias_trabajados);
            else
                $mediaHoras = '';

            return view('pdf.informe', [
                'entrada' => $entrada,
                'salida' => $salida,
                'fichajesPeriodo' => $fichajesPeriodo,
                'diasTrabajados' => $numero_dias_trabajados,
                'numeroFichajes' => $numero_fichajes,
                'totalPeriodo' => $total_periodo,
                'numeroOlvidados' => $numero_olvidados,
                'mediaHoras' => $mediaHoras,
                'user' => $user,
            ]);
        }
    }

    public function downloadExcelWorkers(Request $request) {
        $name = $request->entrada . '-' . $request->salida . '.xls';
        return Excel::download(new workersTable($request->entrada, $request->salida, $request->id), $name);
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
