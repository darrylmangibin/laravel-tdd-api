<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TasksChangeStatusController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TodoListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('todo-list', [TodoListController::class, 'index'])
//     ->name('todo-list.index');

// Route::get('todo-list/{todo_list}', [TodoListController::class, 'show'])
//     ->name('todo-list.show');

// Route::post('todo-list', [TodoListController::class, 'store'])
//     ->name('todo-list.store');

// Route::delete('todo-list/{todo_list}', [TodoListController::class, 'destroy'])
//     ->name('todo-list.destroy');

// Route::patch('todo-list/{todo_list}', [TodoListController::class, 'update'])
//     ->name('todo-list.update');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo-list', TodoListController::class);

    Route::apiResource('todo-list.task', TasksController::class)
        ->shallow();

    Route::patch('task/{task}/change-status', [TasksChangeStatusController::class, 'changeStatus'])->name('task.change_status.update');
});


Route::post('/register', RegisterController::class)->name('user.register');
Route::post('/login', LoginController::class)->name('user.login');
