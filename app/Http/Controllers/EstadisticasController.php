<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DB;

use App\Models\Fichaje;
use App\Models\User;
use App\Models\Project;

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
            ->whereNotNull('stopped_at')
            ->sum('total_time');
            $total_periodo = $this->minutesToHours($total_minutes_periodo);
            $ultimoFichaje = Fichaje::where('user_id', $user->id)->orderBy('started_at', 'desc')->first();
            if($total_minutes_periodo > 0){
                $minutes_per_day = Fichaje::where('user_id', $user->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
                ->groupBy(DB::raw("DATE_FORMAT(started_at, '%d-%m-%Y')"))->orderBy('started_at', 'asc')
                ->select(DB::raw("(sum(total_time)) as total_time"), 'started_at')
                ->get();
                $total_minutes_semana = Fichaje::where('user_id', $user->id)->whereNotNull('stopped_at')
                ->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
                ->orderBy('started_at', 'asc')
                ->select(DB::raw('sum(total_time) as minutes'), DB::raw("DATE_FORMAT(created_at,'%u') as weeks"))
                ->groupBy('weeks')->get();
            } else {
                $minutes_per_day = [];
                $total_minutes_semana = [];
            }
            //return $total_minutes_semana;
            if($numero_dias_trabajados)
                $mediaHoras = $this->minutesToHours($total_minutes_periodo/$numero_dias_trabajados);
            else
                $mediaHoras = '';

            if($user->company->has_projects) {
                $projects = Project::where('id_empresa', $user->company->id)->leftJoin('project_hours', 'project_hours.project_id', '=', 'projects.id')
                ->where('project_hours.user_id', $user->id)->groupBy('projects.id')
                ->whereBetween('project_hours.created_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
                ->select('projects.*', DB::raw('sum(total_time) as total_time'))->get();
                $totalProjects = Project::where('id_empresa', $user->company->id)->leftJoin('project_hours', 'project_hours.project_id', '=', 'projects.id')
                ->where('project_hours.user_id', $user->id)
                ->whereBetween('project_hours.created_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
                ->sum('total_time');
            } else {
                $projects = [];
                $totalProjects = [];
            }

            return view('pages.informe', [
                'entrada' => $entrada,
                'salida' => $salida,
                'fichajesPeriodo' => $fichajesPeriodo,
                'diasTrabajados' => $numero_dias_trabajados,
                'numeroFichajes' => $numero_fichajes,
                'total_minutes_periodo' => $total_minutes_periodo,
                'totalPeriodo' => $total_periodo,
                'numeroOlvidados' => $numero_olvidados,
                'mediaHoras' => $mediaHoras,
                'userId' => $userId,
                'ultimoFichaje' => $ultimoFichaje,
                'minutes_per_day' => $minutes_per_day,
                'total_minutes_semana' => $total_minutes_semana,
                'totalProjects' => $totalProjects,
                'projects' => $projects
            ]);
        } else {
            return redirect()->route('login');
        }
    }

    public function dashboard(Request $request) {
        if($user = Auth::user()) {

            $startDate = $request->input('start', date('Y-01-01'));
            $endDate = $request->input('end', date('Y-12-31'));

            // Retrieve the data from the database
            $results = Fichaje::selectRaw("MONTH(started_at) as month, SUM(TIMESTAMPDIFF(MINUTE, started_at, stopped_at)) as total_minutes")
            ->where("user_id", $user->id)
            ->whereBetween("started_at", [$startDate, $endDate])
            ->groupBy("month")
            ->get();

            // Extract the labels and data from the results
            // Create an array with all the month names
            $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            // Create an array with the data for each month, initialized to zero
            $chartData = array_fill(0, 12, 0);

            // Iterate through the results of the query, and update the data for each month
            foreach ($results as $result) {
                $chartData[$result->month - 1] = number_format(($result->total_minutes / 60), 2);
            }
            return view('pages.dashboard', [
                'labels' => $labels,
                'chartData' => $chartData,
            ]);
        }
    }

    // public function chartData() {
    //     if($user = Auth::user()) {
    //         // Retrieve the data from the database
    //         $results = Fichaje::selectRaw("MONTH(started_at) as month, SUM(TIMESTAMPDIFF(MINUTE, started_at, stopped_at)) as total_minutes")
    //         ->where("user_id", $userId)
    //         ->groupBy("month")
    //         ->get();

    //         // Extract the labels and data from the results
    //         $labels = [];
    //         $chartData = [];
    //         foreach ($results as $result) {
    //             $labels[] = $result->month;
    //             $chartData[] = $result->total_minutes;
    //         }

    //         // Return the data as a JSON response
    //         return response()->json($data);
    //     }
    // }




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
