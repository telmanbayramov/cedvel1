<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('faculty')
            ->where('status', 1)
            ->get();
        return response()->json($departments, 200);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);



        $department = Department::create([
            'name' => $validated['name'],
            'faculty_id' => $validated['faculty_id'],
        ]);

        return response()->json(['message' => 'Kafedra uğurla yaradılmışdır', 'data' => $department], 201);
    }

    public function show($id)
    {
        $department = Department::with('faculty:id,name')->where('status', '1')->findOrFail($id);
        return response()->json(
            $department,
            200
        );
    }


    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $department->update($validated);

        return response()->json(['message' => 'Kafedra uğurla yeniləndi', 'data' => $department], 200);
    }

    public function delete($id)
    {
        $department = Department::where('status', 1)->findOrFail($id);

        if ($department->status == 0) {
            return response()->json([
                'message' => 'Bu kafedra zaten ləğv edilib',
            ], 400);
        }
        $department->update(['status' => 0]);
        return response()->json([
            'message' => 'Kafedra ləğv edilib',
        ], 200);
    }
}
