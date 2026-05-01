<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/Features', function () {
    return view('Features');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ── Todo API ── */
    Route::get('/todo/bootstrap',                       [TodoController::class, 'bootstrap']);
    Route::post('/todo/lists',                          [TodoController::class, 'storelist']);
    Route::delete('/todo/lists/{id}',                   [TodoController::class, 'destroyList']);
    Route::post('/todo/tasks',                          [TodoController::class, 'storeTask']);
    Route::patch('/todo/tasks/{id}',                    [TodoController::class, 'updateTask']);
    Route::delete('/todo/tasks/{id}',                   [TodoController::class, 'destroyTask']);
    Route::post('/todo/tasks/{taskId}/subtasks',        [TodoController::class, 'storeSubtask']);
    Route::patch('/todo/subtasks/{id}',                 [TodoController::class, 'updateSubtask']);
    Route::post('/todo/tags',                           [TodoController::class, 'storeTag']);
    Route::delete('/todo/tags/{id}',                    [TodoController::class, 'destroyTag']);
    Route::post('/todo/tasks/{taskId}/tags/{tagId}',    [TodoController::class, 'assignTag']);

    Route::post('/todo/tasks/{taskId}/attachments',     [TodoController::class, 'storeAttachment']);
    Route::delete('/todo/attachments/{id}',             [TodoController::class, 'destroyAttachment']);
});

require __DIR__.'/auth.php';
