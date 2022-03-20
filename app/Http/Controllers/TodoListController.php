<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    public function index()
    {
        $list = TodoList::all();

        return response($list);
    }

    public function show(TodoList $todo_list)
    {
        return response($todo_list);
    }
}
