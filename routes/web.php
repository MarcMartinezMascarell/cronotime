<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ViewController;
use App\Http\Controllers\FichajesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\PDFGeneratorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [FichajesController::class, 'indexFichar'])->name('home');
Auth::routes();

Route::get('/home', [FichajesController::class, 'indexFichar'])->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade');
	 Route::get('icons', function () {return view('pages.icons');})->name('icons');
	 Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

    //FICHAJES
    Route::post('fichajeOlvidado', [FichajesController::class, 'fichajeOlvidado'])->name('fichaje.olvidado');
    Route::get('/fichar', [FichajesController::class, 'indexFichar'])->name('fichar.view');
    Route::get('/crearFichaje', [FichajesController::class, 'setFichaje'])->name('setFichaje');
    Route::post('/delete/fichaje', [FichajesController::class, 'delete'])->name('fichaje.delete');
    Route::post('/setSalida', [FichajesController::class, 'setSalida'])->name('fichaje.salida');

	//ADMINISTRACIÓN
		//EMPRESAS
		Route::get('companies', [EmpresaController::class, 'index'])->name('company.index');
		Route::get('createCompany', [EmpresaController::class, 'createCompany'])->name('company.create');
		Route::post('createCompany', [EmpresaController::class, 'storeCompany'])->name('company.store');
		Route::get('deleteCompany/{id}', [EmpresaController::class, 'deleteCompany'])->name('company.delete');

		Route::get('workers/{id}', [AdminController::class, 'showWorkers'])->name('workers.show');
		Route::get('createProfile', [AdminController::class, 'createProfile'])->name('profile.create');
		Route::post('storeProfile', [AdminController::class, 'storeProfile'])->name('profile.store');

    //ESTADÍSTICAS
        //EMPLEADOS
        Route::get('informes', [EstadisticasController::class, 'informe'])->name('estadisticas.informe');

    //PDFGENERATOR
        Route::get('downloadInformePDF', [PDFGeneratorController::class, 'download'])->name('pdf.download');
});

//VIEWS

