<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::where('status', 1)
            ->with('specialities')
            ->get();

        return response()->json($faculties);
    }
    public function show($id)
    {
        $faculty = Faculty::with(['specialities' => function ($query) {
            $query->where('status', '1');
        }])
            ->findOrFail($id);

        if ($faculty->status == '0') {
            return response()->json(['message' => "Bu fakültə qeyri-aktivdir və daxil olmaq mümkün deyil"], 403);
        }

        return response()->json($faculty);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $faculty = Faculty::create([
            'name' => $validated['name']
        ]);
        return response()->json(['message' => 'Fakültə uğurla yaradıldı', 'data' => $faculty], 201);
    }
    public function update(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);
        if ($faculty->status == '0') {
            return response()->json(['message' => 'Fəal olmayan fakültələr yenilənə bilməz'], 403);
        }
        $validated = $request->validate([
            'name' => 'string|max:255'
        ]);

        $faculty->update($validated);

        return response()->json(['message' => 'Fakültə uğurla yeniləndi', 'data' => $faculty]);
    }
    public function delete($id)
    {
        $faculty = Faculty::where('status', '1')->findOrFail($id);
        if ($faculty->status == 0) {
            return response()->json([
                'message' => 'Bu fakültə artıq deaktiv edilib.'
            ], 400);
        }
        $faculty->update(['status' => 0]);
        return response()->json([
            'message' => 'Fakültə uğurla deaktiv edildi.'
        ], 200);
    }
}
