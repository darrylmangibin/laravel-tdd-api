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
        $list = TodoList::factory()->create([
            'name' => 'my list'
        ]);

        $response = $this->getJson(route('todo-list.index'));

        $this->assertEquals(1, count($response->json()));
        $this->assertEquals('my list', $response->json()[0]['name']);
    }

    public function test_fetch_single_todo_list()
    {
        $list = TodoList::factory()->create();

        $response = $this->getJson(route('todo-list.show', $list->id))
            ->assertOk()
            ->json();

        $this->assertEquals($response['name'], $list->name);
    }
}
