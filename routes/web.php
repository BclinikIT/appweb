<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioImcController;
use App\Http\Controllers\FormularioImcInvitadosController;

use Inertia\Inertia;
use App\Http\Controllers\ImcWebhookController;
use App\Http\Controllers\CribadoWebhookController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('/formularioimc', FormularioImcController::class);
    Route::resource('/formularioimcinvitados', FormularioImcInvitadosController::class);
    Route::resource('/users', UserController::class);

    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

});


Route::get('webhook/imc_download', [ImcWebhookController::class, 'pdf']);
Route::post('webhook/imc_formulario', [ImcWebhookController::class, 'handle']);
Route::post('webhook/imc_invitacion', [ImcWebhookController::class, 'handleImcInvitacion']);

Route::post('webhook/cribado_cotizacion', [CribadoWebhookController::class, 'handleCribadoCotizacion']);

