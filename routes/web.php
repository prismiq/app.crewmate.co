<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\MatrixController;
use App\Http\Controllers\CalendarController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/crew', [CrewController::class, 'index'])->name('crew.index');
    Route::get('/crew/create', [CrewController::class, 'create'])->name('crew.create');
    Route::post('/crew', [CrewController::class, 'store'])->name('crew.store');
    Route::get('/crew/{crew}', [CrewController::class, 'show'])->name('crew.show');
    Route::get('/crew/{crew}/edit', [CrewController::class, 'edit'])->name('crew.edit');
    Route::put('/crew/{crew}', [CrewController::class, 'update'])->name('crew.update');
    Route::delete('/crew/{crew}', [CrewController::class, 'destroy'])->name('crew.destroy');

    Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
    Route::put('/certificates/{certificate}', [CertificateController::class, 'update'])->name('certificates.update');

    Route::get('/vessels', [VesselController::class, 'index'])->name('vessels.index');
    Route::post('/vessels', [VesselController::class, 'store'])->name('vessels.store');
    Route::put('/vessels/{vessel}', [VesselController::class, 'update'])->name('vessels.update');
    Route::delete('/vessels/{vessel}', [VesselController::class, 'destroy'])->name('vessels.destroy');
    Route::get('/certificates/matrix', [MatrixController::class, 'index'])->name('certificates.matrix');
    Route::get('/certificates/matrix/export', [MatrixController::class, 'export'])->name('certificates.matrix.export');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
