<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        return response($tasks, Response::HTTP_OK);
    }

    public function store(TaskRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
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
