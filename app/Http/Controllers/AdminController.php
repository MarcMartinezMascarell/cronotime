<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Models\Horario;
use App\Models\Empresa;

class AdminController extends Controller
{

    public function showWorkers($id, Request $request) {
        $entrada = $request->get('start', Carbon::now()->startOfMonth());
        $salida = $request->get('end', Carbon::today());
        if(auth()->user()->hasRole('superAdmin')) {
            $users = User::get();
            $empresa = Empresa::find(1);
            return view('admin.showWorkers', ['empresa' => $empresa, 'workers' => $users,
            'entrada' => $entrada, 'salida' => $salida]);
        } else {
            if(auth()->user()->company->id == $id) {
                $empresa = Empresa::find($id);
                //return $empresa->workers;
                return view('admin.showWorkers', ['empresa' => $empresa, 'workers' => $empresa->workers,
                'entrada' => $entrada, 'salida' => $salida]);
            }
            return redirect()->back()->withError(__('No tienes permiso para acceder.'));
        }

    }

    public function createProfile() {
        return view('admin.newProfile');
    }

    public function storeProfile(Request $request) {
        if(auth()->user()->company->workers_limit > auth()->user()->company->workersCount()) {
            $user = User::firstOrCreate(['email' => $request->email],
            ['name' => $request->name, 'surname' => $request->surname, 'password' => Hash::make($request->password),
            'job' => $request->cargo, 'id_empresa' => $request->company]);
            if(!$user->horario) {
                $horario = new Horario;
                $horario->Monday = $request->lunes;
                $horario->Tuesday = $request->martes;
                $horario->Wednesday = $request->miercoles;
                $horario->Thursday = $request->jueves;
                $horario->Friday = $request->viernes;
                $horario->Saturday = $request->sabado;
                $horario->Sunday = $request->domingo;
                $horario->save();
                $user->horario = $horario->id;
                $user->assignRole('trabajador');
                $user->save();
                $user->sendWelcomeNotification(Carbon::now()->addDay());
            }
            return redirect()->route('workers.show', [$user->company->id]);
        } else {
            return redirect()->back()->withError(__('Has alcanzado el límite de trabajadores. Aumenta tu plan para poder añadir más trabajadores.'));
        }
    }

    public function toggleAdmin(Request $request) {
        $user = User::find($request->id);
        if($user->hasRole('superAdmin')) {
            return redirect()->back()->withError(__('No puedes cambiar el rol de este usuario.'));
        }
        if($user->hasRole('administrador')) {
            $user->removeRole('administrador');
            $user->assignRole('trabajador');
        } else {
            $user->removeRole('trabajador');
            $user->assignRole('administrador');
        }
        return redirect()->back();
    }


    public function sendSetPassword($user) {
            $token = Str::random(64);
            Mail::send('email.forgetPassword', ['token' => $token, 'user' => $user], function($message, $user) {
            $message->to($user->email);
            $message->subject('Reset Password');
        });
    }
}
