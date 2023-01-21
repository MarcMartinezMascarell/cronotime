<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class Project extends Model
{
    use HasFactory;

    public function totalTime($entrada, $salida) {
        $totalTime = ProjectHours::where('project_id', $this->id)->whereBetween('created_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])->sum('total_time');
        return $totalTime;
    }

    public function topUser($entrada, $salida) {
        $totalTime = ProjectHours::where('project_id', $this->id)->whereBetween('created_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
        ->groupBy('user_id')->selectRaw('sum(total_time) as total_time, user_id')->get();
        if($totalTime->count() > 0) {
            $user = User::find($totalTime[0]->user_id);
            return $user;
        } else {
            return null;
        }
    }

    public function contributors($entrada, $salida) {
        $totalTime = ProjectHours::where('project_id', $this->id)
        ->whereBetween('project_hours.created_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
        ->groupBy('user_id')->leftJoin('users', 'project_hours.user_id', 'users.id')
        ->selectRaw('sum(total_time) as total_time, users.id, users.name, users.surname')->orderBy('total_time')->get();
        return $totalTime;
    }

    public function numberContributors($entrada, $salida) {
        $totalTime = ProjectHours::where('project_id', $this->id)
        ->whereBetween('project_hours.created_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
        ->groupBy('user_id')->get();
        return $totalTime->count();
    }
}
