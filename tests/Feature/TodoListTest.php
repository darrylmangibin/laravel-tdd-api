<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_todo_list()
    {
        TodoList::create([
            'name' => 'my list'
        ]);

        $response = $this->getJson('api/todo-list');

        $this->assertEquals(1, count($response->json()));
    }
}
