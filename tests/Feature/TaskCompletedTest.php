<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TaskCompletedTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_can_be_change_status()
    {
        $task = $this->createTasks();

        $response = $this->patchJson(route('task.change_status.update', $task->id), [
            'status' => Task::STARTED
        ]);

        $this->assertDatabaseHas('tasks', ['status' => Task::STARTED]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($response->json()['status'], Task::STARTED);
    }
}
