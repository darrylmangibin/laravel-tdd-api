<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    public function index() {
        $tasks = Task::all();

        return response($tasks, Response::HTTP_OK);
    }
}
