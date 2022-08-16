<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $list;
    public function setUp():void
    {
        parent::setUp();
        $user = $this->authUser();
        $this->list = $this->createTodoList(['name' => 'my list','user_id' => $user->id]);
    }

    /** @test */
    public function fetch_all_todo_list()
    {
        $this->createTodoList();
        $response = $this->getJson(route('todo-list.index'));

        $this->assertEquals(1,count($response->json()));
    }

    /** @test */
    public function fetch_single_todo_list()
    {
        $response = $this->getJson(route('todo-list.show',$this->list->id))
                        ->assertOk()
                        ->json();

        $this->assertEquals($response['name'],$this->list->name);
    }

    /** @test */
    public function store_new_todo_list()
    {
        $list = TodoList::factory()->make(); // make() => create but not store in DB

        $response = $this->postJson(route('todo-list.store'),['name' => $list->name])
            ->assertCreated()
            ->json();

        $this->assertEquals($list->name,$response['name']);
        $this->assertDatabaseHas('todo_lists',['name' => $list->name]);
    }

    /** @test */
    public function while_storing_todo_list_name_field_is_require()
    {
        $this->withExceptionHandling();
        $this->postJson(route('todo-list.store'))
            // ->assertStatus(422); // 422 status code for validation errors
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function delete_todo_list()
    {
        $this->deleteJson(route('todo-list.destroy',$this->list))
            ->assertNoContent();

        $this->assertDatabaseMissing('todo_lists',['name' => 'my list']);
    }

    /** @test */
    public function update_todo_list()
    {
        $this->putJson(route('todo-list.update',$this->list),['name' => 'updated list'])
            ->assertOk();

        $this->assertDatabaseHas('todo_lists',['id' => $this->list->id , 'name' => 'updated list']);
    }

    /** @test */
    public function while_updating_todo_list_name_field_is_require()
    {
        $this->withExceptionHandling();

        $this->putJson(route('todo-list.update',$this->list))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
