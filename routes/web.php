<?php

use Illuminate\Support\Facades\Route;

use Spatie\WelcomeNotification\WelcomesNewUsers;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\FichajesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\PDFGeneratorController;
use App\Http\Controllers\WelcomeController;

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

Route::group(['middleware' => ['web', WelcomesNewUsers::class,]], function () {
    Route::get('welcome/{user}', [WelcomeController::class, 'showWelcomeForm'])->name('welcome');
    Route::post('welcome/{user}', [WelcomeController::class, 'savePassword']);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
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
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

    //Cambiar idiona
    Route::post('change/language', [UserController::class, 'language'])->name('locale.change');

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
        Route::put('updateLogo', [EmpresaController::class, 'updateLogo'])->name('company.updateLogo');

		Route::get('workers/{id}', [AdminController::class, 'showWorkers'])->name('workers.show');
		Route::get('createProfile', [AdminController::class, 'createProfile'])->name('profile.create');
		Route::post('storeProfile', [AdminController::class, 'storeProfile'])->name('profile.store');

    //ESTADÍSTICAS
        //EMPLEADOS
        Route::get('informes', [EstadisticasController::class, 'informe'])->name('estadisticas.informe');

    //DOWLOAD FILES
        Route::get('downloadInformePDF', [PDFGeneratorController::class, 'download'])->name('pdf.download');
        Route::get('downloadExcelWorkers', [PDFGeneratorController::class, 'downloadExcelWorkers'])->name('excel.download');

    //ADMIN
        //Show PDF TEMPLATE
        Route::get('pdfTemplate', [PDFGeneratorController::class, 'pdfTemplate'])->name('pdf.template');
});

//VIEWS

