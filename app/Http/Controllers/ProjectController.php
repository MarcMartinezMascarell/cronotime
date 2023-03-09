<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DB;

use App\Models\Fichaje;
use App\Models\Project;
use App\Models\ProjectHours;

class ProjectController extends Controller
{
    public function index()
    {
        if($user = Auth::user()->hasAnyRole('administrador|superAdmin') && Auth::user()->company->has_projects == 1) {
            $projects = Project::where('id_empresa', auth()->user()->id_empresa)->get();
            return view('admin.showProjects', compact('projects'));
        } else {
            return redirect()->route('home')->withError('No tienes permiso para hacer eso');
        }
    }

    public function createProject(){
        if($user = Auth::user()->hasAnyRole('administrador|superAdmin') && Auth::user()->company->has_projects == 1) {
            return view('admin.createProject');
        } else {
            return redirect()->route('home')->withError('No tienes permiso para hacer eso');
        }
    }

    public function storeProject(Request $request){
        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->client = $request->client;
        $project->status = 'active';
        $project->id_empresa = auth()->user()->company->id;
        $project->save();
        return redirect()->route('projects.index');
    }

    public function showProject($id, Request $request){
        if(Project::find($id)) {
            if($user = Auth::user()->hasAnyRole('administrador|superAdmin') && Auth::user()->company->has_projects == 1 && Project::find($id)->id_empresa == Auth::user()->company->id) {
                $project = Project::find($id);
                $entrada = $request->get('start', Carbon::now()->startOfMonth());
                $salida = $request->get('end', Carbon::today());
                return view('admin.project', ['project' => $project, 'entrada' => $entrada, 'salida' => $salida]);
            } else {
                return redirect()->route('home')->withError('No tienes permiso para hacer eso');
            }
        }
        return redirect()->back()->withError('No tienes permiso para hacer eso');
    }

    public function assignHours() {
        if($user = Auth::user()) {
            $projects = Project::where('id_empresa', Auth::user()->company->id)->get();
            return view('pages.assignHours', ['projects' => $projects]);
        } else {
            return redirect()->route('login');
        }
    }

    public function saveProjectHours(Request $request){
        if($user = Auth::user()) {
            $timeAssigned = 0;
            foreach($request->all() as $value) {
                if(is_array($value)) {
                    $projectHours = new ProjectHours;
                    $projectHours->project_id = (int)$value[0];
                    $projectHours->user_id = Auth::user()->id;
                    $projectHours->total_time = (int)$value[1];
                    $projectHours->save();
                    $timeAssigned += (int)$value[1];
                }
            }
            $user->minutes_to_assign -= $timeAssigned;
            $user->update();
            return redirect()->route('project.assignHours');
        } else {
            return redirect()->route('login');
        }

    }
}
