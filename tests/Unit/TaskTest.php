<?php

namespace Tests\Unit;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_task_belongs_to_a_todo_list()
    {
        $list = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $list->id]);

        $this->assertInstanceOf(TodoList::class,$task->todo_list);
    }

    /** @test */
    public function delete_a_task_remove_its_tasks_also()
    {
        $list = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $list->id]);
        $task2 = $this->createTask();

        $list->delete();

        $this->assertDatabaseMissing('todo_lists' , ['id' => $list->id]);
        $this->assertDatabaseMissing('tasks' , ['id' => $task->id]);
        $this->assertDatabaseHas('tasks',['id' => $task2->id]);
    }
}
