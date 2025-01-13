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
            'department_id' => 'required|integer|exists:departments,id',
        ]);

        $department = Department::find($validated['department_id']);

        if (!$department) {
            return response()->json(['error' => 'Department not found'], 404);
        }

        $disciplin = Discipline::create([
            'name' => $validated['name'],
            'department_id' => $department->id,
        ]);

        return response()->json($disciplin, 201);
    }


    public function update(Request $request, $id)
    {
        $disciplin = Discipline::where('id', $id)->where('status', 1)->first();

        if (!$disciplin) {
            return response()->json([
                'message' => 'Bu disciplin ya da statusu 0 olan disciplin bulunamadı.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|integer|exists:departments,id',
        ]);

        $department = Department::find($validated['department_id']);

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
                'department_id' => $department->id,
                'status' => $disciplin->status,
            ],
        ]);
    }

    public function destroy($id)
    {
        $disciplin = Discipline::where('status', '1')->findOrFail($id);
        $disciplin->update(['status' => '0']);
        return response()->json([
            'message' => 'Disciplin başarıyla silindi.'
        ], 200);
    }
}
