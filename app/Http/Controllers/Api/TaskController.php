<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Helper\JsonResponseController;
use App\Models\TaskList;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends JsonResponseController
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $tasks = TaskList::where([
            'status' => false,
        ])->orderBy('due_date')->paginate(10);

        return $this->sendPaginateResponse($tasks->toArray(), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
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
        $request->due_date = Carbon::parse($request->due_date)->format('Y-m-d');

        $task = TaskList::create([
            'title' => $request->title,
            'due_date' => $request->due_date,
        ]);
        return $this->sendResponse($task, "Task created Successfully", 201);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function complete(Request $request)
    {
        $taskRequest = $request->all();
        $request_fields = [
            'id' => 'required',
        ];
        $validator = Validator::make($taskRequest, $request_fields,);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['result' => 0, 'message' => $errors[0]]);
        }

        //get requested task form DB
        $task = TaskList::find($request->id);
        if (empty($task))
            return $this->sendError("Task with id not found.");
        $task->status = true;
        $task->save();
        return $this->sendResponse($task, "Task has been completed.", 200);
    }

    /**
     * @param $task_id
     * @return JsonResponse
     */
    public function delete($task_id)
    {
        $task = TaskList::find($task_id);

        if (empty($task))
            return $this->sendError("Task with id not found.");
        $task->delete();

        return $this->sendResponse([], "Task deleted.", 200);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function filter(Request $request)
    {
        $tasks = TaskList::when($request->query('title'),
            function ($query, $title) {
                return $query->where('title', 'like', "%{$title}%");
            })
            ->when($request->query('due_date'),
                function ($query, $due_on) {
                    if ($due_on === 'today') {
                        return $query->where('due_date', Carbon::today());
                    } elseif ($due_on === 'this_week') {
                        return $query->whereBetween('due_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    } elseif ($due_on === 'next_week') {
                        return $query->whereBetween('due_date', [Carbon::now()->addDays(7)->startOfWeek(), Carbon::now()->addDays(7)->endOfWeek()]);
                    } elseif ($due_on === 'overdue') {
                        return $query->where('due_date', '<', Carbon::now());
                    }
                    return $query;
                })
            ->orderBy('due_date')
            ->paginate(10);

        return $this->sendPaginateResponse($tasks->toArray(), 200);
    }

}
