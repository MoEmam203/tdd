<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        $this->authUser();
    }
    /** @test */
    public function fetch_all_tasks_of_a_todo_list()
    {
        $list = $this->createTodoList();
        $list2 = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $list->id]);
        $this->createTask(['todo_list_id' => $list2->id]);

        $response = $this->getJson(route('todo-list.tasks.index',$list))
            ->assertOk()
            ->json();

        $this->assertEquals(1,count($response));
        $this->assertEquals($task->title, $response[0]['title']);
        $this->assertEquals($response[0]['todo_list_id'],$list->id);
    }

    /** @test */
    public function store_a_task_for_a_todo_list()
    {
        $list = $this->createTodoList();
        $task = Task::factory()->make();

        $this->postJson(route('todo-list.tasks.store',$list),['title' => $task->title])
            ->assertCreated();

        $this->assertDatabaseHas('tasks',['title' => $task->title,'todo_list_id' => $list->id]);
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
