<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentEmbedding;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('embeddings')->get();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'roll_no' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $student = Student::create($data);

        return redirect()->route('students.index')->with('success', 'Student created');
    }

    public function enroll($id)
    {
        $student = Student::findOrFail($id);
        return view('students.enroll', compact('student'));
    }

    public function storeEmbedding(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $data = $request->validate([
            'descriptor' => 'required|array',
        ]);

        StudentEmbedding::create([
            'student_id' => $student->id,
            'descriptor' => json_encode($data['descriptor']),
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function labels()
    {
        $students = Student::with('embeddings')->get();
        $out = $students->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'descriptors' => $s->embeddings->pluck('descriptor')->map(function ($d) { return $d; })->values(),
            ];
        });

        return response()->json($out);
    }
}
