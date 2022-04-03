<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\JsonResponseController;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends JsonResponseController
{
    //
    public function store(Request $request)
    {
        $taskRequest = $request->all();
        $request_fields = [
            'title' => 'required',
            'due_date' => 'required'
        ];
        $validator = Validator::make($taskRequest, $request_fields,);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['result' => 0, 'message' => $errors[0]]);
        }

        $task = TaskList::create([
            'title' => $request->title,
            'due_date' => $request->due_date,
        ]);
        return $this->sendPaginateResponse($task->toArray(),'200');
    }

    public function index()
    {
        $tasks = TaskList::where([
            'status' => false,
        ])->orderBy('due_date')->paginate(5);

        return $this->sendPaginateResponse($tasks->toArray(),'201');
//        return response($tasks->toArray(), 201, ['Content-Type', 'application/json']);
    }
}
