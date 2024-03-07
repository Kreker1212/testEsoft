<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\TaskController;

Route::get('/', [IndexController::class, 'showLoginOrTasks'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'allTask'])->name('show.home');
    Route::post('/filtered_task', [TaskController::class, 'showFilterTask'])->name('show.filter.task');

    Route::post('/filtered_task_resp', [TaskController::class, 'showFilterTaskResponsible'])->name('task.resp.show');

    Route::post('/task', [TaskController::class, 'changeTask'])->name('change.task');
    Route::post('/add_task/submit', [TaskController::class, 'addTaskSubmit'])->name('add.task.submit');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

