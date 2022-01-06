<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TasksController extends Controller {

    private static array $SORTING_FIELDS_MAP = [
        'id' => 'id',
        'ddate' => 'ddate',
        'last_modified' => 'edate',
    ];

    public function index(Request $request) {
        $request->validate([
            'filters' => 'json',
            'sorting' => 'json',
            'current_page' => 'nullable|integer',
            'current_limit' => 'nullable|integer'
        ]);

        $base_query = Task::query();
        $items_query = $base_query->clone();

        /* FILTERS */
        $filters = json_decode($request->input('filters'));
        $subject_id = $filters->subject_id ?? null;
        if ($subject_id) {
            $items_query->where('subject_id', '=', $subject_id);
        }
        $status_id = $filters->status_id ?? null;
        if ($status_id) {
            $items_query->where('status_id', '=', $status_id);
        }

        /* SORTING */
        $sorting = json_decode($request->input('sorting'));
        $field = $sorting->field ?? null;
        $dir = $sorting->dir ?? null;
        if (array_key_exists($field, self::$SORTING_FIELDS_MAP) && $dir) {
            $items_query->orderBy(self::$SORTING_FIELDS_MAP[$field], $dir);
        }

        /* PAGINATION */
        $current_page = $request->input('current_page', 1);
        $current_limit = $request->input('current_limit', 10);
        $items_query
            ->offset(($current_page * $current_limit) - $current_limit)
            ->limit($current_limit);

        return response()->json([
            'items' => $items_query->get()->map(function (Model $model) {
                return $model->getViewObject();
            })->toArray(),
            'total' => $base_query->count(),
        ]);
    }

    public function get(Task $task) {
        return response()->json($task->getViewObject());
    }

    public function add(Request $request) {
        $task = $this->extractTaskFromRequest($request);
        $task->adate = time();
        $task->save();
        return response()->json();
    }

    public function edit(Task $task, Request $request) {
        $task = $this->extractTaskFromRequest($request, $task);
        $task->edate = time();
        $task->update();
        return response()->json();
    }

    /**
     * @param Request $request
     * @param Task|null $task
     * @return Task
     */
    private function extractTaskFromRequest(Request $request, Task $task = null): Task {
        $request->validate([
            'subject_id' => 'required|integer',
            'description' => 'nullable|string',
            'status_id' => 'required|integer',
            'ddate' => 'nullable|date_format:Y-m-d',
        ]);
        $request->merge([
            'ddate' => strtotime($request->input('ddate'))
        ]);
        $task = $task ?? new Task();
        $task->subject_id = $request->input('subject_id');
        $task->description = $request->input('description');
        $task->status_id = $request->input('status_id');
        $task->ddate = $request->input('ddate');
        return $task;
    }

    public function delete(Task $task) {
        $task->delete();
        return response()->json();
    }
}
