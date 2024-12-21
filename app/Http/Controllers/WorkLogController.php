<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkLogController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->search_date)) {
            $work_date = $request->search_date;
        } else {
            $work_date = date('Y-m-d');
        }
        $work_logs = WorkLog::where([
            'user_id' => Auth::user()->id,
            'work_date' => $work_date
            ])->get();

        return view('member.work-log', ['work_logs' => $work_logs, 'request' => $request]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_date' => 'required|date',
            'hours' => 'required|numeric',
            'activity' => 'required|max:3000',
            'note' => 'max:3000',
        ]);

        try {
            WorkLog::create([
                'user_id' => Auth::user()->id,
                'work_date' => $request->work_date,
                'hours' => $request->hours,
                'activity' => $request->activity,
                'note' => $request->note,
            ]);

            $notification = array(
                'message' => "Added successfully.",
                'alert-type' => 'success'
            );
        } catch (Exception $e) {
            $notification = array(
                'message' => "Addition failed. ".$e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->route('work-log.index')->with($notification);
    }

    public function edit(string $id)
    {
        $work_log = WorkLog::find($id);
        $work_logs = WorkLog::where([
            'user_id' => Auth::user()->id,
            'work_date' => $work_log->work_date
            ])->get();

        return view('member.work-log', ['work_log' => $work_log, 'work_logs' => $work_logs]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'work_date' => 'required|date',
            'hours' => 'required|numeric',
            'activity' => 'required|max:3000',
            'note' => 'max:3000',
        ]);

        try {
            $work_log = WorkLog::find($id);
            $work_log->update([
                'work_date' => $request->work_date,
                'hours' => $request->hours,
                'activity' => $request->activity,
                'note' => $request->note,
            ]);

            $notification = array(
                'message' => "Updated successfully.",
                'alert-type' => 'success'
            );
        } catch (Exception $e) {
            $notification = array(
                'message' => "Update failed. ".$e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->route('work-log.index')->with($notification);
    }

    public function destroy(string $id)
    {
        try {
            WorkLog::find($id)->delete();

            $notification = array(
                'message' => "Deleted successfully.",
                'alert-type' => 'success'
            );
        } catch (Exception $e) {
            $notification = array(
                'message' => "Deletion failed. ".$e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->route('work-log.index')->with($notification);
    }

    public function report(Request $request)
    {
        $date_from = date("Y-m-01");
        $date_to = date("Y-m-t");

        if (isset($request->date_from)) {
            $date_from = $request->date_from;
        }
        if (isset($request->date_to)) {
            $date_to = $request->date_to;
        }

        $work_logs = WorkLog::where('user_id', Auth::user()->id)
            ->whereBetween('work_date', [$date_from, $date_to])
            ->orderBy('work_date')
            ->orderBy('id')->get();

        return view('member.work-log-report', ['work_logs' => $work_logs, 'request' => $request]);
    }

    public function management_report(Request $request)
    {
        $date_from = date("Y-m-01");
        $date_to = date("Y-m-t");

        if (isset($request->date_from)) {
            $date_from = $request->date_from;
        }
        if (isset($request->date_to)) {
            $date_to = $request->date_to;
        }

        $users = User::all();

        $work_logs = WorkLog::whereBetween('work_date', [$date_from, $date_to])
        ->where(function($query) use ($request) {
            if (isset($request->user_id)) {
                $query->where('user_id', $request->user_id);
            }
        })->orderBy('work_date');

        return view('manager.work-log-report', ['work_logs' => $work_logs, 'users' => $users, 'request' => $request]);
    }

    public function management_report_download(Request $request)
    {

        $date_from = date("Y-m-01");
        $date_to = date("Y-m-t");

        if (isset($request->date_from)) {
            $date_from = $request->date_from;
        }
        if (isset($request->date_to)) {
            $date_to = $request->date_to;
        }

        $work_logs = WorkLog::join('users', 'work_logs.user_id', '=', 'users.id')
        ->whereBetween('work_date', [$date_from, $date_to])
        ->where(function($query) use ($request) {
            if (isset($request->user_id)) {
                $query->where('user_id', $request->user_id);
            }
        })
        ->get(['firstname', 'lastname', 'work_date', 'hours', 'activity', 'note'])
        ->toArray();

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=work-log_'.$date_from.'-'.$date_to.'_'.date('YmdHis').'.csv',
            'Expires' => '0',
            'Pragma' => 'public'
        ];

        // add headers for each column in the CSV download
        array_unshift($work_logs, array_keys($work_logs[0]));

        $callback = function() use ($work_logs)
        {
            $out = fopen('php://output', 'w');
            foreach ($work_logs as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        };

        // download as a csv file
        return response()->stream($callback, 200, $headers);
    }
}
