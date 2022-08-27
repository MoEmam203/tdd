<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\WebServiceController;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;

Route::middleware(['auth:sanctum'])->group(function () { 
    Route::apiResource('todo-list',TodoListController::class);
    
    Route::apiResource('todo-list.tasks',TaskController::class)->except('show')->shallow();

    Route::resource('label',LabelController::class);

    Route::get('web_service/connect/{web_service}',[WebServiceController::class,'connect'])->name('web-service.connect');
    Route::post('web_service/callback',[WebServiceController::class,'callback'])->name('web-service.callback');
    Route::post('web_service/store/{web_service}',[WebServiceController::class,'store'])->name('web-service.store');
});

Route::post('/register',RegistrationController::class)->name('auth.register');
Route::post('login',LoginController::class)->name('auth.login');