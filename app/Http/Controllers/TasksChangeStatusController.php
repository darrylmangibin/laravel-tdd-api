<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskChangeStatusRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TasksChangeStatusController extends Controller
{
    public function changeStatus(Task $task, TaskChangeStatusRequest $request)
    {
        $task->update($request->all());

        return response(new TaskResource($task), Response::HTTP_OK);
    }
}
