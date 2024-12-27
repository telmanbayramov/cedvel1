<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semester = Semester::where('status', '1')->get();
        return response()->json($semester);
    }
    public function show($id)
    {
        $semester = Semester::where('status', '1')->findOrFail($id);
        return response()->json($semester);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|string|max:255',
            'semester_num' => 'required|string|max:255'
        ]);

        $semester = Semester::create([
            'year' => $validated['year'],
            'semester_num' => $validated['semester_num']
        ]);

        return response()->json([
            'message' => 'Semestr elave edildi',
            'data' => $semester
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);

        $validated = $request->validate([
            'year' => 'required|string|max:255',
            'semester_num' => 'required|string|max:255'
        ]); 

        $semester->update([
            'year' => $validated['year'],
            'semester_num' => $validated['semester_num']
        ]);

        return response()->json([
            'message' => 'Semestr güncellendi',
            'data' => $semester
        ]);
    }
    public function destroy($id)
    {
        $semester = Semester::where('status', '1')->findOrFail($id);
        $semester->update(['status' => '0']);
        return response()->json([
            'message' => 'semestr silindi',
        ]);
    }
}
