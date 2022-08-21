<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Empresa;
use App\Models\User;

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

        //Añadir administrador si existe, o crear uno nuevo si no existe
        $user = User::firstOrCreate(['email' => $request->email],
        ['name' => $request->adminName, 'password' => Hash::make($request->password)  ]);

        $user->id_empresa = $empresaId;
        $user->assignRole('administrador');
        $user->save();

        return redirect()->route('company.index');

    }

    public function deleteCompany($id) {
        $empresa = Empresa::find($id);
        $empresa->delete();
        return redirect()->back();
    }
}
