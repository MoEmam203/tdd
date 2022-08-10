<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function fetch_all_tasks_of_a_todo_list()
    {
        $list = $this->createTodoList();
        $task = $this->createTask();

        $response = $this->getJson(route('todo-list.tasks.index',$list))
            ->assertOk()
            ->json();

        $this->assertEquals(1,count($response));
        $this->assertEquals($task->title, $response[0]['title']);
    }

    /** @test */
    public function store_a_task_for_a_todo_list()
    {
        $list = $this->createTodoList();
        $task = Task::factory()->make();

        $this->postJson(route('todo-list.tasks.store',$list),['title' => $task->title])
            ->assertCreated();

        $this->assertDatabaseHas('tasks',['title' => $task->title]);
    }

    /** @test */
    public function delete_a_task_from_database()
    {
        $task = $this->createTask();

        $this->deleteJson(route('tasks.destroy',$task))
            ->assertNoContent();

        $this->assertDatabaseMissing('tasks',['id' => $task->id,'title' => $task->title]);
    }

    /** @test */
    public function update_a_task_for_a_todo_list()
    {
        $task = $this->createTask();

        $this->putJson(route('tasks.update',$task),['title' => 'updated Task'])
            ->assertOk();

        $this->assertDatabaseHas('tasks',['id' => $task->id , 'title' => 'updated Task']);
    }
}
