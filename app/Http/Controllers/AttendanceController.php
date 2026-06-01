<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function scan()
    {
        return view('attendance.scan');
    }

    public function mark(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
        ]);

        $student = Student::findOrFail($data['student_id']);

        $date = Carbon::now()->toDateString();
        $time = Carbon::now()->toTimeString();

        // prevent duplicate marking for same day
        $exists = Attendance::where('student_id', $student->id)->where('date', $date)->first();
        if ($exists) {
            return response()->json(['status' => 'already']);
        }

        Attendance::create([
            'student_id' => $student->id,
            'date' => $date,
            'time' => $time,
            'status' => 'present',
        ]);

        return response()->json(['status' => 'ok']);
    }
}
