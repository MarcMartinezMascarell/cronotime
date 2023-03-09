<?php

namespace App\Http\Controllers;

use App\Models\Fichaje;
use App\Models\User;
use App\Models\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DB;


class FichajesController extends Controller
{
    public function indexFichar() {
        if($user = Auth::user()) {
            //App::setLocale('ca');
            $fichajesHoy = Fichaje::where('user_id', $user->id)->whereDate('started_at', Carbon::today())->orderBy('started_at')->get();
            $ultimoFichaje = Fichaje::where('user_id', $user->id)->orderBy('started_at', 'desc')->first();
            $total_minutes_ended = Fichaje::where('user_id', $user->id)->whereNotNull('stopped_at')
            ->whereDate('started_at', Carbon::today())
            ->sum('total_time');
            $last_fichaje_not_ended = (Fichaje::where('user_id', $user->id)->whereDate('started_at', Carbon::today())
            ->where('stopped_at', null)->select('started_at')->first());
            if($last_fichaje_not_ended)
                $total_minutes_not_ended = Carbon::now()->diffInMinutes($last_fichaje_not_ended->started_at);
            else
                $total_minutes_not_ended = 0;
            $total_minutes = $total_minutes_ended + $total_minutes_not_ended;
            $total_hoy = $this->minutesToHours($total_minutes);

            $total_minutes_semana = Fichaje::where('user_id', $user->id)->whereNotNull('stopped_at')
            ->whereBetween('started_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::SUNDAY)])
            ->sum('total_time');
            $total_minutes_semana += $total_minutes_not_ended;
            $total_semana = $this->minutesToHours($total_minutes_semana);

            $minutes_per_day = Fichaje::where('user_id', $user->id)->whereBetween('started_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::SUNDAY)])
            ->groupBy(DB::raw("DATE_FORMAT(started_at, '%d-%m-%Y')"))->orderBy('started_at', 'asc')
            ->select(DB::raw("(sum(total_time)) as total_time"), 'started_at')
            ->get();

            $horario = User::where('users.id', Auth()->id())->leftJoin('horarios', 'users.horario', '=', 'horarios.id')
            ->select('horarios.Monday', 'horarios.Tuesday', 'horarios.Wednesday', 'horarios.Thursday',
            'horarios.Friday', 'horarios.Saturday', 'horarios.Sunday')->first();
            $semanaPrevisto = array_sum($horario->toArray());

            //return json_encode($minutes_per_day);
            return view('pages.ficharView', [
                'fichajesHoy' => $fichajesHoy,
                'ultimoFichaje' => $ultimoFichaje,
                'total_hoy' => $total_hoy,
                'total_minutes_hoy' => $total_minutes,
                'total_semana' => $total_semana,
                'total_minutes_semana' => $total_minutes_semana,
                'minutes_per_day' => $minutes_per_day,
                'semanaPrevisto' => $semanaPrevisto,
            ]);
        } else {
            return redirect()->route('login');
        }
    }


    public function setFichaje() {

        if($user = Auth::user()) {
            $ultimoFichaje = Fichaje::where('user_id', $user->id)->orderBy('started_at', 'desc')->first();
            $isNew = false;
            //return $ultimoFichaje;
            if($ultimoFichaje && $ultimoFichaje->stopped_at == null) {
                $ultimoFichaje->stopped_at = Carbon::now();
                //$total_time = $ultimoFichaje->started_at->diff($ultimoFichaje->stopped_at);
                $total_time = $ultimoFichaje->stopped_at->diffInMinutes($ultimoFichaje->started_at);
                $ultimoFichaje->total_time = $total_time;
                $ultimoFichaje->save();
                $user->minutes_to_assign = $user->minutes_to_assign + $total_time;
                $user->update();
            } else {
                $nuevoFichaje = Fichaje::create([
                    'user_id' => $user->id,
                    'started_at' => Carbon::now(),
                ]);
                $isNew = true;
                $nuevoFichaje->save();
            }
            if($ultimoFichaje && $ultimoFichaje->stopped_at != null && $isNew == false) {
                if(Auth::user()->company->has_projects == 1) {
                    $projects = Project::where('id_empresa', Auth::user()->company->id)->get();
                    return view('pages.assignHours', ['projects' => $projects, 'ultimoFichaje' => $ultimoFichaje]);
                } else {
                    return redirect()->route('fichar.view');
                }
            } else {
                return redirect()->route('fichar.view');
            }

        } else {
            return redirect()->route('login');
        }
    }

    public function fichajeOlvidado(Request $request) {
        if($user = Auth::user()) {
            if($request->salida_yes == "true")
                $salida = $request->salida;
            else
                $salida = null;
            $ultimoFichaje = Fichaje::where('user_id', $user->id)->orderBy('started_at', 'desc')->first();
            if($ultimoFichaje && $ultimoFichaje->stopped_at->gt($request->started_at) && $salida == null) {
                return redirect()->back()->with(['error' => 'No puedes crear un fichaje sin salida anterior al último fichaje']);
            } else {
                $total_time = Carbon::parse($salida)->diffInMinutes($request->entrada);
                $nuevoFichaje = Fichaje::create([
                    'user_id' => $user->id,
                    'started_at' => $request->entrada,
                    'stopped_at' => $salida,
                    'total_time' => $total_time,
                    'forgot' => 1,
                ]);
                $nuevoFichaje->save();
                if($user->company->has_projects == 1 && $nuevoFichaje->stopped_at != null) {
                    $user->minutes_to_assign = $user->minutes_to_assign + $total_time;
                    $user->update();
                    $projects = Project::where('id_empresa', Auth::user()->company->id)->get();
                    return view('pages.assignHours', ['projects' => $projects]);
                } else {
                    return redirect()->route('fichar.view');
                }
                return redirect()->back();
            }
        }
    }

    public function setSalida(Request $request) {
        if($user = Auth::user()) {
            $fichaje = Fichaje::find($request->idFichaje);
            $fichaje->stopped_at = $request->salida;
            $fichaje->total_time = $fichaje->stopped_at->diffInMinutes($fichaje->started_at);
            $fichaje->update();
            if($user->company->has_projects == 1) {
                $user->minutes_to_assign = $user->minutes_to_assign + $fichaje->total_time;
                $user->update();
                $projects = Project::where('id_empresa', Auth::user()->company->id)->get();
                return view('pages.assignHours', ['projects' => $projects]);
            } else {
                return redirect()->back();
            }
        }
        return redirect()->back();
    }


    public function delete(Request $request) {
        if($user = Auth::user()) {
            $fichaje = Fichaje::find($request->idFichaje);
            if($request->type == 'entrada') {
                $fichaje->delete();
            } else {
                if($user->company->has_projects == 1) {
                    $user->minutes_to_assign = $user->minutes_to_assign - $fichaje->total_time;
                    $user->update();
                }
                $fichaje->stopped_at = null;
                $fichaje->total_time = null;
                $fichaje->save();
            }
            return redirect()->route('fichar.view');
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
