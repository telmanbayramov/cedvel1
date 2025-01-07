<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;
use App\Models\Department;

class DisciplinController extends Controller
{
    public function index()
    {
        $disciplin = Discipline::where('status', '1')
            ->with('department')
            ->get();

        return response()->json($disciplin);
    }

    public function show($id)
    {
        $disciplin = Discipline::where('status', '1')
            ->with('department')
            ->findOrFail($id);

        return response()->json($disciplin);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_name' => 'required|string',
        ]);

        $department = Department::where('name', $validated['department_name'])->first();

        if (!$department) {
            return response()->json(['error' => 'Department not found'], 404);
        }

        $disciplin = Discipline::create([
            'name' => $validated['name'],
            'department_id' => $department->id,
            'status' => 1,
        ]);

        return response()->json($disciplin, 201);
    }

    public function update(Request $request, $id)
    {
        $disciplin = Discipline::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_name' => 'required|string|exists:departments,name', // department_name üzerinden doğrulama
        ]);

        $department = \App\Models\Department::where('name', $validated['department_name'])->first();

        if (!$department) {
            return response()->json([
                'message' => 'Belirtilen department bulunamadı.',
            ], 404);
        }

        $disciplin->update([
            'name' => $validated['name'],
            'department_id' => $department->id,
        ]);

        return response()->json([
            'message' => 'Disciplin başarıyla güncellendi.',
            'disciplin' => [
                'name' => $disciplin->name,
                'department_name' => $department->name,
                'status' => $disciplin->status,
            ],
        ]);
    }
    public function destroy($id)
    {
        $disciplin = Discipline::findOrFail($id);

        $disciplin->update(['status' => '0']);

        return response()->json([
            'message' => 'Disciplin başarıyla silindi.',
            'disciplin' => [
                'name' => $disciplin->name,
                'department_id' => $disciplin->department_id,
                'status' => $disciplin->status,
            ],
        ], 200);
    }
}
