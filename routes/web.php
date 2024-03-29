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
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProjectController;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::view('changelog', 'pages.changelog');

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
        Route::post('toggleProjects', [EmpresaController::class, 'toggleProjects'])->name('company.toggleProjects');

		Route::get('workers/{id}', [AdminController::class, 'showWorkers'])->name('workers.show');
        Route::delete('deleteWorker', [AdminController::class, 'deleteWorker'])->name('workers.delete');
        Route::patch('resetHours', [AdminController::class, 'resetUserUnusedHours'])->name('workers.resetHours');
		Route::get('createProfile', [AdminController::class, 'createProfile'])->name('profile.create');
		Route::post('storeProfile', [AdminController::class, 'storeProfile'])->name('profile.store');
        Route::patch('toggleAdmin', [AdminController::class, 'toggleAdmin'])->name('workers.toggleAdmin');

        //Proyectos
        Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('createProject', [ProjectController::class, 'createProject'])->name('project.create');
        Route::post('storeProject', [ProjectController::class, 'storeProject'])->name('project.store');
        Route::patch('inactiveProject/{id}', [ProjectController::class, 'inactiveProject'])->name('project.inactive');
        Route::delete('deleteProject/{id}', [ProjectController::class, 'deleteProject'])->name('project.delete');
        Route::get('showProject/{id}', [ProjectController::class, 'showProject'])->name('project.show');
        Route::get('editProject/{id}', [ProjectController::class, 'editProject'])->name('project.edit');
        Route::patch('updateProject/{id}', [ProjectController::class, 'updateProject'])->name('project.update');
        Route::get('assignHours', [ProjectController::class, 'assignHours'])->name('project.assignHours');
        Route::post('saveProjectHours', [ProjectController::class, 'saveProjectHours'])->name('project.saveProjectHours');


    //ESTADÍSTICAS
        //EMPLEADOS
        Route::get('informes/', [EstadisticasController::class, 'informe'])->name('estadisticas.informe');

        //ESTADÍSTICAS
        Route::get('dashboard/', [EstadisticasController::class, 'dashboard'])->name('estadisticas.dashboard');
        Route::get('chart-data', [EstadisticasController::class, 'chartData']);

    //CALENDARIO
        Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('get-events', [CalendarController::class, 'getEvents']);
        Route::post('add-event', [CalendarController::class, 'addEvent'])->name('calendar.addEvent');

    //DOWLOAD FILES
        Route::get('downloadInformePDF', [PDFGeneratorController::class, 'download'])->name('pdf.download');
        Route::get('downloadExcelWorkers', [PDFGeneratorController::class, 'downloadExcelWorkers'])->name('excel.download');

    //ADMIN
        //Show PDF TEMPLATE
        Route::get('pdfTemplate', [PDFGeneratorController::class, 'pdfTemplate'])->name('pdf.template');
});

//VIEWS

