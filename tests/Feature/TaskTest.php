<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private $tasks;

    public function setUp(): void
    {
        parent::setUp();

        $this->tasks = $this->createTasks();
    }

    public function test_fetch_all_tasks_of_a_todo_list()
    {
        $list = $this->createTodoLists();
        $response = $this->getJson(route('todo-list.task.index', $list->id))->assertOk()->json();

        $this->assertEquals($this->tasks->title, $response[0]['title']);
    }

    public function test_store_task_for_a_todo_list(Type $var = null)
    {
        $task = Task::factory()->make();
        $list = $this->createTodoLists();
        $response = $this->postJson(route('todo-list.task.store', $list->id), [
            'title' => $task->title
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_store_task_validation()
    {
        $this->withExceptionHandling();
        $list = $this->createTodoLists();

        $response = $this->postJson(route('todo-list.task.store', $list->id));

        $this->assertArrayHasKey('errors', $response->json());
        $this->assertArrayHasKey('title', $response->json()['errors']);
    }

    public function test_delete_task_from_database()
    {
        $this->withExceptionHandling();

        $response = $this->deleteJson(route('task.destroy', $this->tasks->id));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('tasks', [
            'id' => $this->tasks->id,
        ]);
    }

    public function test_delete_task_invalid_id()
    {
        $this->withExceptionHandling();
        $invalid_id = 194;

        $response = $this->deleteJson(route('task.destroy', $invalid_id));

        $this->assertDatabaseCount('tasks', $this->tasks->count());
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_task_for_a_todo()
    {
        $response = $this->patchJson(route('task.update', $this->tasks->id), [
            'title' => 'Updated'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('tasks', [
            'id' => $this->tasks->id,
            'title' => 'Updated'
        ]);
    }

    public function test_update_task_invalid_request()
    {
        $this->withExceptionHandling();

        $response = $this->patchJson(route('task.update', $this->tasks->id));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertArrayHasKey('errors', $response->json());
        $this->assertArrayHasKey('title', $response->json()['errors']);
    }
}
