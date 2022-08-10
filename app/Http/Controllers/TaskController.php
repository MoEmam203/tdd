<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index()
    {
        return response(Task::all());
    }

    public function store(Request $request)
    {
        return Task::create(['title' => $request->title]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response('',Response::HTTP_NO_CONTENT);
    }
    
    public function update(Task $task,Request $request)
    {
        $task->update(['title' => $request->title]);
        return $task;
    }
}
