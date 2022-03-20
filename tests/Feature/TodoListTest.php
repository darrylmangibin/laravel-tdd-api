<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
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

    public function test_store_todo_list()
    {
        $todo_list = TodoList::factory()->make();

        $response = $this->postJson(route('todo-list.store'), [
            'name' => $todo_list->name
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('todo_lists', [
            'name' => $response->json()['name']
        ]);
        $this->assertEquals($todo_list->name, $response->json()['name']);
    }

    public function test_todo_list_validation()
    {
        $this->withExceptionHandling();

        $response = $this->postJson(route('todo-list.store'))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);


        $response->assertJsonValidationErrors('name');
        $this->assertArrayHasKey('errors', $response->json());
        $this->assertArrayHasKey('name', $response->json()['errors']);
    }
}
