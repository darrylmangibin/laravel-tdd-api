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

    public function setUp(): void
    {
        parent::setUp();

        $this->list = TodoList::factory()->create();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_todo_list()
    {

        $response = $this->getJson(route('todo-list.index'));

        $this->assertEquals($this->list->count(), count($response->json()));
        $this->assertEquals($this->list->name, $response->json()[0]['name']);
    }

    public function test_fetch_single_todo_list()
    {
        $response = $this->getJson(route('todo-list.show', $this->list->id))
            ->assertOk()
            ->json();

        $this->assertEquals($response['name'], $this->list->name);
    }
}
