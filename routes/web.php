<?php

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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('success', 'Logout berhasil!');
})->name('logout');


Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::get('/tasks/edit/{id}', [TaskController::class, 'edit'])->name('tasks.edit');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');


Route::delete('/tasks/delete/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

Route::patch('/tasks/{task}/approve', [TaskController::class, 'approve'])->name('tasks.approve');
Route::patch('/tasks/{task}/reject', [TaskController::class, 'reject'])->name('tasks.reject');
Route::patch('/tasks/{task}/revision', [TaskController::class, 'revision'])->name('tasks.revision');

// Route untuk menampilkan form edit
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');

// Route untuk proses update task
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');





// Route setelah login berhasil
Route::get('/dashboard', function () {
    return 'Selamat datang di dashboard!';
})->middleware('auth');