<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    public function index()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    // public function show($id)
    public function show()
    {
        $schedule = \App\Schedule::find(1);
        if (!$schedule)
          $schedule = \App\Schedule::create();

        $this->authorize('show-schedule', $schedule);

        return view('schedules.show', compact('schedule'));
    }

    // public function edit($id)
    public function edit($id)
    {
        $schedule = \App\Schedule::findOrFail($id);

        $this->authorize('edit-schedule', $schedule);

        return view('schedules.edit', compact('schedule'));
    }

    // public function update(Request $request, $id)
    public function update(Request $request, $id)
    {
        $schedule = \App\Schedule::findOrFail($id);

        $this->authorize('edit-schedule', $schedule);

        $payload = array_merge($request->all(), [
          'is_period' => $request->input('is_period') ? $request->input('is_period') : 0,
          'input_start_date' => $request->input('is_period') ? $request->input('input_start_date') : null,
          'input_end_date' => $request->input('is_period') ? $request->input('input_end_date') : null
        ]);

        $schedule->update($payload);

        flash()->success('자료 입력 일정을 변경하였습니다.');
        return redirect(route('schedules.show'));
    }

    public function destroy($id)
    {
        //
    }
}
