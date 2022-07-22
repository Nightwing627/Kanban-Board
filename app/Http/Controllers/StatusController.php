<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Task;

class StatusController extends Controller
{
    //

    public function destory($id)
    {
        // delete all tasks belong to the status
        $res = Task::where('status_id', $id)->delete();
        dd ($res);
        Status::where('id', $id)->delete();

        return $res;
    }
}
