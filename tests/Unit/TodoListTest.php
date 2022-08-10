<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class TodoListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_todo_list_has_many_tasks()
    {
        $list = $this->createTodoList();
        $this->createTask(['todo_list_id' => $list->id]);

        $this->assertInstanceOf(Task::class,$list->tasks->first());
    }
}
