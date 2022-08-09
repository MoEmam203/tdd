<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoListController;

Route::apiResource('todo-list',TodoListController::class);

Route::get('tasks',[TaskController::class,'index'])->name('tasks.index');
Route::post('tasks',[TaskController::class,'store'])->name('tasks.store');
Route::delete('tasks/{task}',[TaskController::class,'destroy'])->name('tasks.destroy');