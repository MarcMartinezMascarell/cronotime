<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function totalTime() {
        $totalTime = ProjectHours::where('project_id', $this->id)->sum('total_time');
        return $totalTime;
    }

    public function totalTimeByUser() {
        $totalTime = ProjectHours::where('project_id', $this->id)->groupBy('user_id')->selectRaw('sum(total_time) as total_time, user_id')->get();
        $user = User::find($totalTime[0]->user_id);
        return $user;
    }
}
