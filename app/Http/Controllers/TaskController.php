<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    public function index()
    {
        $statusList = auth()->user()->statuses()->with('tasks')->get();

        return view('tasks.index', compact('statusList'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:56'],
            'description' => ['required', 'string'],
            'status_id' => ['required', 'exists:statuses,id']
        ]);

        $order = $request->user()->tasks()->max('order') + 1;
        $params = $request->only('title', 'description', 'status_id');
        $params['order'] = $order;

        return $request->user()
            ->tasks()
            ->create($params);
    }

    public function sync(Request $request) {
        $this->validate(request(), [
            'columns' => ['required', 'array']
        ]);

        foreach ($request->columns as $status) {
            foreach ($status['tasks'] as $i => $task) {
                $order = $i + 1;
                if ($task['status_id'] !== $status['id']
                    || $task['order'] !== $order) {
                    request()->user()->tasks()
                        ->find($task['id'])
                        ->update(['status_id' => $status['id'], 'order' => $order]);
                }
            }
        }

        return $request->user()->statuses()->with('tasks')->get();
    }

    public function show(Task $task)
    {
        //
    }

    public function edit(Task $task)
    {
        //
    }

    public function update(Request $request, Task $task)
    {
        //
    }

    public function destroy(Task $task)
    {
        //
    }

    public function calculateTotals(array $installments): array {
        $reult = array("USD" => 0, "CAD" => 0, "AUD" => 0, "EUR" => 0);

        foreach ($installments as $installment) {
            foreach ($result as $key => $item) {
                if ($installment->currency == $key) {
                    $result[$key] += $installment->price;
                }
            }
        }

        return $result;
    }
}
