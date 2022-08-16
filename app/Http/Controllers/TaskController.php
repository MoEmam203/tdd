<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index(TodoList $todo_list)
    {
        return $todo_list->tasks;
    }

    public function store(TaskRequest $request,TodoList $todo_list)
    {
        return $todo_list->tasks()->create($request->validated());
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response('',Response::HTTP_NO_CONTENT);
    }
    
    public function update(Task $task,TaskRequest $request)
    {
        return $task->update($request->validated());
    }
}
