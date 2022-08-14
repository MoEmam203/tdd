<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;

Route::middleware(['auth:sanctum'])->group(function () { 
    Route::apiResource('todo-list',TodoListController::class);
    
    Route::apiResource('todo-list.tasks',TaskController::class)->except('show')->shallow();
});

Route::post('/register',RegistrationController::class)->name('auth.register');
Route::post('login',LoginController::class)->name('auth.login');