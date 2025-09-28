<?php

use App\Http\Controllers\ArchivedNoteController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [NoteController::class, 'index'])
    ->middleware('auth')
    ->name('home');

Route::post('/notes', [NoteController::class, 'store'])
    ->middleware('auth')
    ->name('notes.store');

Route::put('/notes/{note}', [NoteController::class, 'update'])
    ->middleware('auth')
    ->name('notes.update');

Route::delete('/notes/{note}', [NoteController::class, 'destroy'])
    ->middleware('auth')
    ->name('notes.destroy');

Route::put('/notes/{note}/archive', [ArchivedNoteController::class, 'store'])
    ->middleware('auth')
    ->name('archived-notes.store');

Route::put('/notes/{note}/restore', [ArchivedNoteController::class, 'destroy'])
    ->middleware('auth')
    ->name('archived-notes.destroy');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
