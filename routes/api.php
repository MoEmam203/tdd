<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;

Route::get('todo-list',[TodoListController::class,'index'])->name('todo-list.index');
Route::get('todo-list/{list}',[TodoListController::class,'show'])->name('todo-list.show');
Route::post('todo-list', [TodoListController::class,'store'])->name('todo-list.store');
Route::delete('todo-list/{list}/delete',[TodoListController::class,'destroy'])->name('todo-list.destroy');
Route::put('todo-list/{list}/update',[TodoListController::class,'update'])->name('todo-list.update');