<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $list;
    public function setUp():void
    {
        parent::setUp();
        $this->list = TodoList::factory()->create();
    }

    /** @test */
    public function fetch_all_todo_list()
    {
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
}
