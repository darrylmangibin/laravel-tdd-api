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

        $this->authUser();
    }

    public function test_fetch_all_tasks_of_a_todo_list()
    {
        $list = $this->createTodoLists();
        $task = $this->createTasks(['todo_list_id' => $list->id]);

        $response = $this->getJson(route('todo-list.task.index', $list->id))->assertOk()->json();

        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
        $this->assertEquals($task->todo_list_id, $response[0]['todo_list_id']);
    }

    public function test_store_task_for_a_todo_list(Type $var = null)
    {
        $list = $this->createTodoLists();
        $task = Task::factory()->make([]);

        $response = $this->postJson(route('todo-list.task.store', $list->id), [
            'title' => $task->title,
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
            'todo_list_id' => $list->id
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
        $task = $this->createTasks();

        $response = $this->deleteJson(route('task.destroy', $task->id));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_delete_task_invalid_id()
    {
        $this->withExceptionHandling();
        $invalid_id = 194;
        $task = $this->createTasks();

        $response = $this->deleteJson(route('task.destroy', $invalid_id));

        $this->assertDatabaseCount('tasks', $task->count());
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_task_for_a_todo()
    {
        $task = $this->createTasks();

        $response = $this->patchJson(route('task.update', $task->id), [
            'title' => 'Updated'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated'
        ]);
    }

    public function test_update_task_invalid_request()
    {
        $this->withExceptionHandling();
        $task = $this->createTasks();

        $response = $this->patchJson(route('task.update', $task->id));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertArrayHasKey('errors', $response->json());
        $this->assertArrayHasKey('title', $response->json()['errors']);
    }
}
