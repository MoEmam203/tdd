<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoListController;

Route::apiResource('todo-list',TodoListController::class);

Route::apiResource('todo-list.tasks',TaskController::class)->except('show')->shallow();