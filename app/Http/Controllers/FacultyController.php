<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::where('status', 1)
            ->with('specialities')->with('departments')
            ->get();

        return response()->json($faculties);
    }
    public function show($id)
    {
        $faculty = Faculty::with([
            'departments',
            'specialities' => function ($query) {
                $query->where('status', 1);
            },
        ])->findOrFail($id);

        if ($faculty->status == 0) {
            return response()->json([
                'message' => 'Bu fakültə qeyri-aktivdir və daxil olmaq mümkün deyil',
            ], 403);
        }

        return response()->json([
            'id' => $faculty->id,
            'name' => $faculty->name,
            'status' => $faculty->status,
            'created_at' => $faculty->created_at,
            'updated_at' => $faculty->updated_at,
            'specialities' => $faculty->specialities,
            'departments' => $faculty->departments,
        ]);
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
        $relations = ['departments', 'courses', 'specialities', 'users', 'groups', 'schedules'];
        $related = [];
        foreach ($relations as $relation) {
            if (method_exists($faculty, $relation)) {
                if ($faculty->$relation()->exists()) {
                    $related[] = $relation;
                }
            }
        }
        if (!empty($related)) {
            return response()->json([
                'message' => 'Bu fakültə ilə əlaqəli məlumatlar mövcuddur.Əvvəlcə onları silməlisiniz',
                'related' => $related,
            ], 400);
        }

        $faculty->update(['status' => 0]);

        return response()->json([
            'message' => 'Fakültə uğurla deaktiv edildi.',
        ], 200);
    }
}
