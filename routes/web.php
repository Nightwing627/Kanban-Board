<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\StatusController;
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
Auth::routes();

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return redirect()->route('tasks.index');
})->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('tasks/sync', [TaskController::class, 'sync'])->name('tasks.sync');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{id}', [TaskController::class, 'destory'])->name('tasks.destory');

    Route::get('statuses', [StatusController::class, 'index'])->name('statuses.index');
    Route::put('statuses', [StatusController::class, 'update'])->name('statuses.update');
    Route::delete('statuses/{id}', [StatusController::class, 'destory'])->name('statuses.destory');
});



// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
