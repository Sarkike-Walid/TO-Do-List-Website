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

    // AJAX Settings Routes
    Route::patch('/profile/ajax', [ProfileController::class, 'updateAjax']);
    Route::patch('/profile/password/ajax', [ProfileController::class, 'updatePasswordAjax']);
    Route::post('/profile/avatar/ajax', [ProfileController::class, 'updateAvatarAjax']);

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

/* ── Admin Routes ── */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/role', [\App\Http\Controllers\AdminController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
    
    Route::get('/tasks', [\App\Http\Controllers\AdminController::class, 'tasks'])->name('tasks');
    Route::delete('/tasks/{id}', [\App\Http\Controllers\AdminController::class, 'deleteTask'])->name('tasks.delete');
    
    Route::get('/logs', [\App\Http\Controllers\AdminController::class, 'logs'])->name('logs');
    
    Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');
});

require __DIR__.'/auth.php';
