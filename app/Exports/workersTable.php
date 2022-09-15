<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Carbon\Carbon;

use App\Models\Empresa;
use App\Models\User;

class workersTable implements FromView
{

    public function __construct($entrada, $salida, $id)
    {
        $this->entrada = $entrada;
        $this->salida = $salida;
        $this->id = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        if(!$this->entrada)
            $entrada = Carbon::now()->startOfMonth();
        if(!$this->salida)
            $salida = Carbon::today();
        if(auth()->user()->hasRole('superAdmin')) {
            $users = User::get();
            $empresa = Empresa::find(1);
        } else {
            if(auth()->user()->company->id == $this->id) {
                $empresa = Empresa::find($this->id);
                //return $empresa->workers;
            }
        }
        return view('pdf.workersTable', ['empresa' => $empresa, 'workers' => $empresa->workers,
        'entrada' => $entrada, 'salida' => $salida]);
    }
}
