<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    
    public function test_get_all_todo_list()
    {
        // preparation / prepare

        // action / preform
        $response = $this->getJson(route('todo-list.index'));

        // assertion / predict
        $this->assertEquals(1,count($response->json()));
    }
}
