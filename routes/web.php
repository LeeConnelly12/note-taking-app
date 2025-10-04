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
    ->can('update', 'note')
    ->name('notes.update');

Route::delete('/notes/{note}', [NoteController::class, 'destroy'])
    ->can('forceDelete', 'note')
    ->name('notes.destroy');

Route::put('/notes/{note}/archive', [ArchivedNoteController::class, 'store'])
    ->can('delete', 'note')
    ->name('archived-notes.store');

Route::put('/notes/{note}/restore', [ArchivedNoteController::class, 'destroy'])
    ->can('restore', 'note')
    ->name('archived-notes.destroy')
    ->withTrashed();

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/google.php';
