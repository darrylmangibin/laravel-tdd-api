<?php

namespace Tests\Feature;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $list;

    public function setUp(): void
    {
        parent::setUp();

        $user = $this->authUser();
        $this->list = $this->createTodoLists(['user_id' => $user->id]);

    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_todo_list()
    {
        $this->createTodoLists();

        $response = $this->getJson(route('todo-list.index'));

        $this->assertEquals(1, count($response->json()));
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
            'name' => $todo_list->name,
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

    public function test_delete_todo_list()
    {
        $response = $this->deleteJson(route('todo-list.destroy', $this->list->id));

        $this->assertDatabaseMissing('todo_lists', ['id' => $this->list->id]);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_delete_todo_list_invalid_id()
    {
        $this->withExceptionHandling();
        $invalid_id = 941;

        $response = $this->deleteJson(route('todo-list.destroy', $invalid_id));

        $todo_list_count = TodoList::count();

        $this->assertDatabaseCount('todo_lists', $todo_list_count);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_todo_list()
    {
        $response = $this->patchJson(route('todo-list.update', $this->list->id), [
            'name' => 'Updated'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('todo_lists', [
            'id' => $this->list->id,
            'name' => 'Updated'
        ]);
    }

    public function test_todo_list_update_validation()
    {
        $this->withExceptionHandling();

        $response = $this->patchJson(route('todo-list.update', $this->list->id))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);


        $response->assertJsonValidationErrors('name');
        $this->assertArrayHasKey('errors', $response->json());
        $this->assertArrayHasKey('name', $response->json()['errors']);
    }
}
