<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{
    FormularioImcController,
    FormularioImcInvitadosController,
    ImcWebhookController,
    CribadoWebhookController,
    UserController,
    CribadoController,
    CribadoEncuestaWebhookController,
    EncuestaCribadoController,
    MetabogramasController,
    MetabogramasWebhookController,
};

// Home Route
Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Authenticated Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Resource Routes
    Route::resources([
        '/imc-formulario' => FormularioImcController::class,
        '/imc-formulario-invitados' => FormularioImcInvitadosController::class,
        '/cribado-form-cotizacion' => CribadoController::class,
        '/encuesta' => EncuestaCribadoController::class,
        '/users' => UserController::class,

    ]);

    Route::resource('/metabograma', MetabogramasController::class)->except(['show']);
    
    Route::get('/metabograma/pro', [MetabogramasController::class, 'view_pro'])->name('metabograma.pro'); // name funciona para definiel el nombre de la ruta
    Route::get('/metabograma/plus', [MetabogramasController::class, 'view_plus'])->name('metabograma.plus');

    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
});


// Webhook Routes
Route::prefix('webhook')->group(function () {
    Route::post('/imc_formulario', [ImcWebhookController::class, 'handle']);
    Route::post('/imc_invitacion', [ImcWebhookController::class, 'handleImcInvitacion']);

    Route::post('/cribado_encuesta', [CribadoEncuestaWebhookController::class, 'handleCribadoEncuesta']);
    Route::get('/cribado_encuesta_download', [CribadoEncuestaWebhookController::class, 'pdf']);

    Route::post('/cribado_cotizacion', [CribadoWebhookController::class, 'handleCribadoCotizacion']);
    Route::get('/cribado_cotizacion_download', [CribadoEncuestaWebhookController::class, 'pdf']);

    Route::post('/metabogramas', [MetabogramasWebhookController::class, 'handleMetabogramas']);

});



Route::prefix('pdf/download')->group(function()
{
    Route::get('/imc_formulario', [ImcWebhookController::class, 'pdf']);
    Route::get('/imc_invitado', [ImcWebhookController::class, 'pdf_imc_invitado']);



});
