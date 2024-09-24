<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\Reportes\ReporteCuentasPorPagarController;
use App\Http\Controllers\Reportes\ReporteDespachosController;
use App\Http\Controllers\Reportes\ReporteIngresosController;
use App\Models\Equipment;
use App\Models\OrdenDeServicio;
use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CajaController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\SorteoController;
use App\Http\Controllers\ArtistaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvitadoController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ListaPrecioController;
use App\Http\Controllers\OrdenDeServicioController;
use App\Http\Controllers\OrdenDespachoController;
use App\Http\Controllers\OrdenIngresoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PresentacionPolloController;
use App\Http\Controllers\VentaController;
use App\Models\OrdenDespacho;
use App\Models\OrdenIngreso;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return Redirect::to('/login');
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard',[Dashboard::class,'home'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::resource('usuarios',UserController::class)->middleware('auth');
Route::resource('invitados', InvitadoController::class)->middleware('auth');
Route::resource('roles', RoleController::class)->middleware('auth');
Route::resource('clientes',ClienteController::class)->middleware('auth');
Route::post('/clientes/search', [ClienteController::class, 'searchDocument'])->name('clientes.search');
Route::get('/clientes/list', [ClienteController::class, 'getClientes'])->name('clientes.list');



/** rutas soft v2.0 */

Route::resource('proyectos', ProyectoController::class)->middleware('auth');
Route::resource('actividades', ActividadController::class)->middleware('auth')->parameters(['actividades' => 'actividad']);
Route::resource('tareas', TareaController::class)->middleware('auth');


/* routes v3.0 Eventos */

Route::resource('artistas', ArtistaController::class);
Route::post('/artista/{id}', [ArtistaController::class, 'getArtistaData'])->name('artista.data');

// routes v4.0 El Triki app
Route::resource('sorteos', SorteoController::class)->middleware('auth');
Route::resource('registros', RegistroController::class)->middleware('auth');





/** soft v1.0  */
Route::resource('equipos',EquipoController::class)->middleware('auth');
Route::resource('ordenes-de-servicio',OrdenDeServicioController::class)->middleware('auth')->parameters(['ordenes-de-servicio' => 'orden',]);

/** ruta para Imagenes equipos */
Route::delete('equipos/{equipo}/delete-imagen-equipo/{id}',  [EquipoController::class, 'deleteImagen'] )->name('equipos.deleteimagen');

Route::get('/invitados/registrar/{invitadoId}', [InvitadoController::class, 'registrar'])->name('invitados.registrar');
Route::get('/invitados/validar-qr/{codigo}', [InvitadoController::class, 'validarQR'])->name('invitados.validar-qr');
Route::get('invitados/generar-pdf/{invitado}', [InvitadoController::class, 'generarPDF'])->name('invitados.generarPDF');



/**
 * App indaves
 */
Route::get('resumen/cajas/{caja}', [CajaController::class,'summary'])->middleware('auth')->name('cajas.resumen');
Route::resource('cajas', CajaController::class)->middleware('auth');
Route::resource('empresas', EmpresaController::class)->middleware('auth');
Route::resource('ordenes-ingreso', OrdenIngresoController::class )->middleware('auth')->parameters(['ordenes-ingreso' => 'ordenIngreso']);
Route::resource('ventas', VentaController::class)->middleware('auth');
Route::get('ventas/{id}/{format}', [VentaController::class,'print'])->name('ventas.print')->middleware('auth');
Route::get('ordenes/{id}', [VentaController::class, 'getOrdenDetalles'])->middleware('auth');
Route::resource('ordenes-de-despacho',OrdenDespachoController::class)->middleware('auth')->parameters(['ordenes-de-despacho' => 'ordenDespacho']);
Route::get('ordenes-de-despacho/crear-venta/{id}', [OrdenDespachoController::class,'venta'])->name('ordenes-de-despacho.venta')->middleware('auth');
Route::get('ordenes-de-despacho/{id}/{format}', [OrdenDespachoController::class,'print'])->name('ordenes-de-despacho.print')->middleware('auth');
Route::resource('lista-de-precios',ListaPrecioController::class)->middleware('auth')->parameters(['lista-de-precios' => 'listaPrecio']);
Route::resource('pagos',PagoController::class)->middleware('auth');
Route::resource('presentacion-pollo',PresentacionPolloController::class)->middleware('auth')->parameters(['presentacion-pollo' => 'presentacionPollo']);


Route::get('inventarios',[InventoryController::class,'index'])->name('inventarios.index')->middleware('auth');

Route::get('reportes/ingresos',[ReporteIngresosController::class,'index'])->name('reportes.ingresos.index')->middleware('auth');
Route::post('reportes/ingresos',[ReporteIngresosController::class,'search'])->name('reportes.ingresos.search')->middleware('auth');
Route::post('reportes/ingresos/export/{format}',[ReporteIngresosController::class,'export'])->name('reportes.ingresos.export')->middleware('auth');


Route::get('reportes/despachos',[ReporteDespachosController::class,'index'])->name('reportes.despachos.index')->middleware('auth');
Route::post('reportes/despachos',[ReporteDespachosController::class,'search'])->name('reportes.despachos.search')->middleware('auth');
Route::post('reportes/despachos/export/{format}',[ReporteDespachosController::class,'export'])->name('reportes.despachos.export')->middleware('auth');

Route::get('reportes/cuentas-por-cobrar',[ReporteCuentasPorPagarController::class,'index'])->name('reportes.cuentas-por-cobrar.index')->middleware('auth');
Route::post('reportes/cuentas-por-cobrar',[ReporteCuentasPorPagarController::class,'search'])->name('reportes.cuentas-por-cobrar.search')->middleware('auth');
Route::post('reportes/cuentas-por-cobrar/export/{format}',[ReporteCuentasPorPagarController::class,'export'])->name('reportes.cuentas-por-cobrar.export')->middleware('auth');





//rutas complementarias

Route::get('/crear-simbolico-storage', function () {
    try {
        Artisan::call('storage:link');
        return "Ruta simbólica para storage creada con éxito.";
    } catch (\Exception $e) {
        return "Error al crear la ruta simbólica para storage: " . $e->getMessage();
    }
});


Route::get('/limpiar-cache', function () {
    try {
        Artisan::call('cache:clear');
        return "Cache limpiada correctamente.";
    } catch (\Exception $e) {
        return "Error al limpiar la cache: " . $e->getMessage();
    }
});


Route::get('/limpiar-configuracion', function () {
    try {
        Artisan::call('config:clear');
        return "Configuración limpiada correctamente.";
    } catch (\Exception $e) {
        return "Error al limpiar la configuración: " . $e->getMessage();
    }
});



Route::get('/url', function () {
    return view('url');
});

require __DIR__.'/auth.php';
