<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Models\TodoList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    public function index()
    {
        $list = TodoList::with('tasks')->get();

        return response($list);
    }

    public function show(TodoList $todo_list)
    {
        return response($todo_list);
    }

    public function store(TodoListRequest $request)
    {
        $list = TodoList::create($request->all());

        return response($list, Response::HTTP_CREATED);
    }

    public function destroy(TodoList $todo_list)
    {
        try {
            return DB::transaction(function () use ($todo_list) {
                if (!$todo_list) {
                    throw new Exception('No data found', Response::HTTP_NOT_FOUND);
                }

                $todo_list->delete();

                return response(['message' => 'Success']);
            });
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()]);
        }
    }

    public function update(TodoList $todo_list, TodoListRequest $request)
    {
        $list = $todo_list->update($request->all());

        return response($list, Response::HTTP_OK);
    }
}
