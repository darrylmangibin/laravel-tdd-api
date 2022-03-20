<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TodoList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    public function index(TodoList $todo_list)
    {
        $tasks = Task::where(['todo_list_id' => $todo_list->id])->get();

        return response($tasks, Response::HTTP_OK);
    }

    public function store(TaskRequest $request, TodoList $todo_list)
    {
        try {
            return DB::transaction(function () use ($request, $todo_list) {
                $request['todo_list_id'] = $todo_list->id;

                $task = Task::create($request->all());

                return response($task, Response::HTTP_CREATED);
            });
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()]);
        }
    }

    public function destroy(Task $task)
    {
        try {
            return DB::transaction(function () use ($task) {
                if (!$task) {
                    throw new Exception('No data found', Response::HTTP_NOT_FOUND);
                }

                $task->delete();

                return response(['message' => 'Successfully deleted'], Response::HTTP_OK);
            });
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()]);
        }
    }

    public function update(TaskRequest $request, Task $task)
    {
        try {
            return DB::transaction(function () use ($request, $task) {
                if (!$task) {
                    throw new Exception('Data not found', Response::HTTP_NOT_FOUND);
                }

                $task->update($request->all());

                return response($task, Response::HTTP_OK);
            });
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
