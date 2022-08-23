<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Horario;
use App\Models\Empresa;

class AdminController extends Controller
{

    public function showWorkers($id) {
        if(auth()->user()->hasRole('superAdmin')) {
            $users = User::get();
            return view('admin.showWorkers', ['workers' => $users]);
        } else {
            $empresa = Empresa::find($id);
            return view('admin.showWorkers', ['empresa' => $empresa, 'workers' => $empresa->workers]);
        }

    }

    public function createProfile() {
        return view('admin.newProfile');
    }

    public function storeProfile(Request $request) {
        if(auth()->user()->company->workers_limit <= auth()->user()->company->workersCount()) {
            $user = User::firstOrCreate(['email' => $request->email],
            ['name' => $request->name, 'surname' => $request->surname, 'password' => Hash::make($request->password),
            'job' => $request->cargo, 'id_empresa' => $request->company]);
            $horario = new Horario;
            $horario->lunes = $request->lunes;
            $horario->martes = $request->martes;
            $horario->miercoles = $request->miercoles;
            $horario->jueves = $request->jueves;
            $horario->viernes = $request->viernes;
            $horario->sabado = $request->sabado;
            $horario->domingo = $request->domingo;

            $horario->save();
            $user->horario = $horario->id;
            $user->assignRole('trabajador');
            $user->save();
            return redirect()->back();
        } else {
            return redirect()->back()->withError('Has alcanzado el límite');
        }
    }
}
