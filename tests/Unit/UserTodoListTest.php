<?php

namespace Tests\Unit;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UserTodoListTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_create_many_tasks()
    {
        $user = $this->authUser();
        $this->createTodoList(['user_id' => $user->id]);

        $this->assertInstanceOf(TodoList::class,$user->todo_lists->first());
    }
}
