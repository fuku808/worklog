<?php

namespace App\Http\Controllers;

use App\Models\TimeTracking;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $time_trackings = TimeTracking::where([
            'user_id' => Auth::user()->id,
            'work_date' => date('Y-m-d')
            ])->orderBy('clocked_in');

        return view('member.clockin-out', ['time_trackings' => $time_trackings]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $clocked_in = date("Y-m-d H:i:s");

        TimeTracking::create([
            'user_id' => Auth::user()->id,
            'work_date' => date('Y-m-d'),
            'clocked_in' => $clocked_in,
            'updated_user_id' => Auth::user()->id,
        ]);

        $notification = array(
            'message' => "Clocked in successfully at $clocked_in",
            'alert-type' => 'success'
        );

        return redirect()->route('clockin-out.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $clocked_out = date("Y-m-d H:i:s");

        $time_tracking = TimeTracking::find($id);
        $total_hours = (strtotime($clocked_out) - strtotime($time_tracking->clocked_in))/3600;

        $time_tracking->update([
            'clocked_out' => $clocked_out,
            'total_hours' => round($total_hours, 2),
            'updated_user_id' => Auth::user()->id,
        ]);

        $notification = array(
            'message' => "Clocked out successfully at $clocked_out",
            'alert-type' => 'success'
        );

        return redirect()->route('clockin-out.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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

        $time_trackings = TimeTracking::where('user_id', Auth::user()->id)
            ->whereBetween('work_date', [$date_from, $date_to])
            ->orderBy('clocked_in');

        return view('member.clockin-out-report', ['time_trackings' => $time_trackings, 'request' => $request]);
    }

    public function management(Request $request)
    {
        $work_date = date("Y-m-d");

        if (isset($request->work_date)) {
            $work_date = $request->work_date;
        }

        $users = User::all();

        $time_trackings = TimeTracking::where('work_date', $work_date)
            ->where('user_id', $request->user_id)
            ->orderBy('clocked_in')->get();

        return view('manager.clockin-out-management', ['time_trackings' => $time_trackings, 'users' => $users, 'request' => $request]);
    }

    public function management_store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'work_date' => 'required|date',
            'clocked_in' => 'required',
            'clocked_out' => 'required',
        ]);

        try {
            $clocked_in = $request->clocked_in;
            $clocked_out = $request->clocked_out;

            $total_hours = (strtotime($clocked_out) - strtotime($clocked_in))/3600;

            if ($total_hours <= 0) {
                throw new Exception("The clocked-out time must be after the clocked-in time.");
            }

            TimeTracking::create([
                'user_id' => $request->user_id,
                'work_date' => $request->work_date,
                'clocked_in' => $clocked_in,
                'clocked_out' => $clocked_out,
                'total_hours' => $total_hours,
                'updated_user_id' => Auth::user()->id,
            ]);

            $notification = array(
                'message' => "Added successfully",
                'alert-type' => 'success'
            );

        } catch (Exception $e) {
            $notification = array(
                'message' => "Addition failed. ".$e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->route('management.clockin-out', ['work_date' => $request->work_date, 'user_id' => $request->user_id])->with($notification);
    }

    public function management_edit(string $id)
    {
        $users = User::all();

        $time_tracking = TimeTracking::find($id);

        $time_trackings = TimeTracking::where('work_date', $time_tracking->work_date)
            ->where('user_id', $time_tracking->user_id)
            ->orderBy('clocked_in')->get();

        return view('manager.clockin-out-management', ['time_tracking' => $time_tracking, 'time_trackings' => $time_trackings, 'users' => $users]);
    }

    public function management_update(Request $request, string $id)
    {
        $validated = $request->validate([
            'clocked_in' => 'required',
            'clocked_out' => 'required',
        ]);

        $time_tracking = TimeTracking::find($id);

        try {
            $clocked_in = $request->clocked_in;
            $clocked_out = $request->clocked_out;

            $total_hours = (strtotime($clocked_out) - strtotime($clocked_in))/3600;

            if ($total_hours <= 0) {
                throw new Exception("The clocked-out time must be after the clocked-in time.");
            }

            $time_tracking->update([
                'clocked_in' => $clocked_in,
                'clocked_out' => $clocked_out,
                'total_hours' => $total_hours,
                'updated_user_id' => Auth::user()->id,
            ]);

            $notification = array(
                'message' => "Updated successfully",
                'alert-type' => 'success'
            );

        } catch (Exception $e) {
            $notification = array(
                'message' => "Update failed. ".$e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->route('management.clockin-out', ['work_date' => $time_tracking->work_date, 'user_id' => $time_tracking->user_id])->with($notification);
    }

    public function management_destroy(string $id)
    {
        $time_tracking = TimeTracking::find($id);

        $work_date = $time_tracking->work_date;
        $user_id = $time_tracking->user_id;

        try {
            $time_tracking->delete();

            $notification = array(
                'message' => "Deleted successfully",
                'alert-type' => 'success'
            );
        } catch (Exception $e) {
            $notification = array(
                'message' => "Deletion failed. ".$e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->route('management.clockin-out', ['work_date' => $work_date, 'user_id' => $user_id])->with($notification);
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

        $time_trackings = TimeTracking::whereBetween('work_date', [$date_from, $date_to])
        ->where(function($query) use ($request) {
            if (isset($request->user_id)) {
                $query->where('user_id', $request->user_id);
            }
        })->orderBy('clocked_in');

        return view('manager.clockin-out-report', ['time_trackings' => $time_trackings, 'users' => $users, 'request' => $request]);
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

        $time_trackings = TimeTracking::join('users', 'time_trackings.user_id', '=', 'users.id')
        ->whereBetween('work_date', [$date_from, $date_to])
        ->where(function($query) use ($request) {
            if (isset($request->user_id)) {
                $query->where('user_id', $request->user_id);
            }
        })->orderBy('clocked_in')
        ->get(['firstname', 'lastname', 'work_date', 'clocked_in', 'clocked_out', 'total_hours'])
        ->toArray();

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=clockedin-out_'.$date_from.'-'.$date_to.'_'.date('YmdHis').'.csv',
            'Expires' => '0',
            'Pragma' => 'public'
        ];

        // add headers for each column in the CSV download
        array_unshift($time_trackings, array_keys($time_trackings[0]));

        $callback = function() use ($time_trackings)
        {
            $out = fopen('php://output', 'w');
            foreach ($time_trackings as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        };

        // download as a csv file
        return response()->stream($callback, 200, $headers);
    }
}
