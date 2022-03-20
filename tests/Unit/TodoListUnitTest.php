<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_todo_list_has_many_tasks()
    {
        $list = $this->createTodoLists();
        $task = $this->createTasks(['todo_list_id' => $list->id]);

        $this->assertInstanceOf(Collection::class, $list->tasks);
        $this->assertInstanceOf(Task::class, $list->tasks->first());
    }

    public function test_todo_list_deleted_all_task_will_be_delete()
    {
        $list = $this->createTodoLists();
        $task = $this->createTasks(['todo_list_id' => $list->id]);
        $task2 = $this->createTasks();

        $list->delete();

        $this->assertDatabaseMissing('todo_lists', ['id' => $list->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $this->assertDatabaseHas('tasks', ['id' => $task2->id]);
    }
}
