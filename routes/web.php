<?php

use App\Http\Controllers\AdminMetricsController;
use App\Http\Controllers\ArancelController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PreAdmisionController;
use App\Http\Controllers\ProtocoloController;
use App\Http\Controllers\ReniecController;
use App\Http\Controllers\RotuladoController;
use App\Http\Controllers\VerificadorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/verificador', [VerificadorController::class, 'index'])->name('verificador.index');
Route::post('/envio/verificar-producto', [VerificadorController::class, 'verificar'])->name('envio.verificar');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/registro', [RegistroController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro', [RegistroController::class, 'registrar']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/app', [ClienteController::class, 'index'])->name('cliente.home');
    Route::get('/app/nuevo', [PreAdmisionController::class, 'index'])->name('envio.nuevo');
    Route::get('/app/envios', [ClienteController::class, 'misEnvios'])->name('cliente.envios');

    Route::post('/envio/guardar', [PreAdmisionController::class, 'guardar'])->name('envio.guardar');
    Route::post('/envio/sugerir-arancel', [ArancelController::class, 'sugerir'])->name('envio.arancel');
    Route::get('/envio/{envio}/descargar', [FormularioController::class, 'descargar'])->name('envio.descargar');
    Route::get('/envio/{envio}/rotulado', [RotuladoController::class, 'descargar'])->name('envio.rotulado');

    Route::post('/envio/{envio}/documentos', [DocumentoController::class, 'store'])->name('envio.documentos.store');
    Route::get('/envio/{envio}/documentos/{documento}/descargar', [DocumentoController::class, 'descargar'])->name('envio.documentos.descargar');
    Route::delete('/envio/{envio}/documentos/{documento}', [DocumentoController::class, 'destroy'])->name('envio.documentos.destroy');

    Route::get('/api/reniec/{dni}', [ReniecController::class, 'consultar'])->name('reniec.consultar');
});

Route::middleware(['auth', 'rol:ventanilla,admin'])->prefix('panel')->name('panel.')->group(function () {
    Route::get('/', [PanelController::class, 'bandeja'])->name('bandeja');
    Route::get('/solicitud/{envio}', [PanelController::class, 'solicitud'])->name('solicitud');
    Route::get('/solicitud/{envio}/editar', [PanelController::class, 'editar'])->name('editar');
    Route::patch('/solicitud/{envio}/datos', [PanelController::class, 'actualizar'])->name('actualizar');
    Route::patch('/solicitud/{envio}/estado', [PanelController::class, 'actualizarEstado'])->name('estado');
    Route::patch('/solicitud/{envio}/servicio', [PanelController::class, 'cambiarServicio'])->name('servicio');
    Route::patch('/solicitud/{envio}/procesar', [PanelController::class, 'procesar'])->name('procesar');
    Route::get('/protocolo', [ProtocoloController::class, 'index'])->name('protocolo');
});

Route::middleware(['auth', 'rol:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/metrics', [AdminMetricsController::class, 'index'])->name('metrics');
});
