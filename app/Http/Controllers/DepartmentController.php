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

    $departmentsWithFacultyName = $departments->map(function ($department) {
        return [
            'id' => $department->id,
            'name' => $department->name,
            'faculty_id' => $department->faculty_id,
            'faculty_name' => $department->faculty ? $department->faculty->name : null,
            'status' => $department->status,
            'created_at' => $department->created_at,
            'updated_at' => $department->updated_at,
        ];
    });

    return response()->json($departmentsWithFacultyName, 200);
}

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $existingDepartment = Department::where('name', $validated['name'])
            ->where('faculty_id', $validated['faculty_id'])
            ->where('status', 0)
            ->first();

        if ($existingDepartment) {
            $existingDepartment->update(['status' => 1]);
            return response()->json(['message' => 'Mövcud fəaliyyətsiz kafedra yenidən aktivləşdirilib', 'data' => $existingDepartment], 200);
        }

        $department = Department::create([
            'name' => $validated['name'],
            'faculty_id' => $validated['faculty_id'],
        ]);

        return response()->json(['message' => 'Kafedra uğurla yaradılmışdır', 'data' => $department], 201);
    }

    public function show($id)
    {
        $department = Department::with('faculty:id,name')->findOrFail($id);

        return response()->json([
            'id' => $department->id,
            'name' => $department->name,
            'faculty_name' => $department->faculty->name ?? null, 
            'status' => $department->status,
            'created_at' => $department->created_at,
            'updated_at' => $department->updated_at,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        if ($department->status == 0) {
            return response()->json(['message' => 'Qeyri-aktiv şöbəni yeniləmək mümkün deyil'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $department->update($validated);

        return response()->json(['message' => 'Kafedra uğurla yeniləndi', 'data' => $department], 200);
    }

    public function delete($id)
    {
        $department = Department::findOrFail($id);
        $department->update(['status' => 0]);
        return response()->json(['message' => 'Kafedra ləğv edilib'], 200);
    }
}
