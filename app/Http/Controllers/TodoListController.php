<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    public function index()
    {
        $lists = auth()->user()->todo_lists;
        return response(TodoListResource::collection($lists));
    }

    public function show(TodoList $todo_list)
    {
        return response(new TodoListResource($todo_list));
    }

    public function store(TodoListRequest $request)
    {
        $list = auth()->user()->todo_lists()->create($request->validated());
        return new TodoListResource($list);
    }

    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();
        return response('',Response::HTTP_NO_CONTENT);
    }

    public function update(TodoListRequest $request,TodoList $todo_list)
    {
        return $todo_list->update($request->validated());
    }
}
