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
        if(Auth::user()->hasRole('administrador') && Auth::user()->company->has_projects == 1) {
            $projects = Project::where('id_empresa', auth()->user()->id_empresa)->where('status', 'active')->get();
            $endedProjects = Project::where('id_empresa', auth()->user()->id_empresa)->where('status', 'inactive')->get();
            return view('admin.showProjects', compact('projects', 'endedProjects'));
        } else if(auth()->user()->hasRole('superAdmin')) {
            $projects = Project::all();
            $endedProjects = Project::where('status', 'inactive')->get();
            return view('admin.showProjects', compact('projects', 'endedProjects'));
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

    public function editProject($id) {
        if($user = Auth::user()->hasAnyRole('administrador|superAdmin') && Auth::user()->company->has_projects == 1 && Project::find($id)->id_empresa == Auth::user()->company->id) {
            $project = Project::find($id);
            return view('admin.editProject', ['project' => $project]);
        } else {
            return redirect()->route('home')->withError('No tienes permiso para hacer eso');
        }
    }

    public function updateProject(Request $request) {
        $project = Project::find($request->id);
        $project->update($request->all());
        return redirect()->route('projects.index');
    }

    public function assignHours() {
        if($user = Auth::user()) {
            $projects = Project::where('id_empresa', Auth::user()->company->id)
            ->orderBy('client', 'asc')->orderBy('updated_at')->get()->groupby('client');
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
                    if((int)$value[1] > 0) {
                        $projectHours = new ProjectHours;
                        $projectHours->project_id = (int)$value[0];
                        $projectHours->user_id = Auth::user()->id;
                        $projectHours->total_time = (int)$value[1];
                        $projectHours->save();
                        $timeAssigned += (int)$value[1];
                    }
                }
            }
            $user->minutes_to_assign -= $timeAssigned;
            $user->update();
            return redirect()->route('project.assignHours');
        } else {
            return redirect()->route('login');
        }
    }

    public function inactiveProject($id) {
        if($user = Auth::user()->hasAnyRole('administrador|superAdmin') && Auth::user()->company->has_projects == 1 && Project::find($id)->id_empresa == Auth::user()->company->id) {
            $project = Project::find($id);
            $project->status = 'inactive';
            $project->update();
            return redirect()->route('projects.index');
        } else {
            return redirect()->route('home')->withError('No tienes permiso para hacer eso');
        }
    }

    public function deleteProject($id){
        if($user = Auth::user()->hasAnyRole('administrador|superAdmin') && Auth::user()->company->has_projects == 1 && Project::find($id)->id_empresa == Auth::user()->company->id) {
            $project = Project::find($id);
            $project->delete();
            return redirect()->route('projects.index');
        } else {
            return redirect()->route('home')->withError('No tienes permiso para hacer eso');
        }
    }
}
