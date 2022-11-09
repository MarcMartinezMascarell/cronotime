<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Empresa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class EmpresaController extends Controller
{
    public function index() {
        $empresas = Empresa::get();
        return view('admin.showCompanies', ['empresas' => $empresas]);
    }

    public function createCompany() {
        return view('admin.newCompany');
    }

    public function storeCompany(Request $request) {
        //Guardar empresa nueva
        $empresa = new Empresa;
        $empresa->nombre = $request->name;
        $empresa->workers_limit = $request->limit;
        if($request->logo_url) {
            $empresa->logo_url = $request->logo;
        }
        $empresa->save();
        $empresaId = $empresa->id;

        //Guardar logo de la empresa
        if($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = str_replace(' ', '', $empresa->nombre) . '_logo.' . $image->getClientOriginalExtension();
            $location = public_path('images/logos/' . $filename);
            $path = $request->file('logo')->storeAs('public/images/logos', $filename);
            $empresa->logo_url = $filename;
            $empresa->save();
        }

        //Añadir administrador si existe, o crear uno nuevo si no existe
        $user = User::firstOrCreate(['email' => $request->email],
        ['name' => $request->adminName, 'password' => Hash::make($request->password)  ]);

        $user->id_empresa = $empresaId;
        $user->assignRole('administrador');
        $user->save();
        $user->sendWelcomeNotification(Carbon::now()->addDay());

        return redirect()->route('company.index');

    }

    public function updateLogo(Request $request) {
        if(auth()->user()->hasRole('administrador')) {
            $empresa = Empresa::find(auth()->user()->company->id);
            if($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = str_replace(' ', '', $empresa->nombre) . '_logo.' . $image->getClientOriginalExtension();
                $location = public_path('images/logos/' . $filename);
                File::delete($location);
                $path = $request->file('logo')->storeAs('public/images/logos', $filename);
                // $empresa->logo_url = $filename;
                // $empresa->save();
            }
            return redirect()->route('fichar.view');
        }
    }

    public function deleteCompany($id) {
        $empresa = Empresa::find($id);
        $empresa->delete();
        return redirect()->back();
    }
}
