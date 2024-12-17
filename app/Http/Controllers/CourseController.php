<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['specialty' => function($query) {
            $query->select('id', 'name'); 
        }])->where('status', 1)->get();
    
        return response()->json($courses, 200);
    }
    
    
    
    public function show($id)
    {
        $cours = Course::where('status', '1')->findOrFail($id);
        return response()->json($cours, 201);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialty_id' => 'required|exists:specialities,id',
        ]);

        $validated['status'] = $validated['status'] ?? 1;

        $existingCourse = Course::where('name', $validated['name'])->where('status', 0)->first();

        if ($existingCourse) {
            $existingCourse->update(['status' => 1]);
            return response()->json(['message' => 'Silinmiş kurs tekrar aktif oldu!', 'course' => $existingCourse], 200);
        }

        $course = Course::create($validated);

        return response()->json(['message' => 'Kurs elave edildi!', 'course' => $course], 201);
    }
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        if ($course->status == 0) {
            return response()->json(['error' => 'Bu kurs silinib ve guncellene bilmez!'], 400);
        }
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'speciality_id' => 'sometimes|exists:specialities,id',
        ]);
        if (isset($validated['name'])) {
            $existingCourse = Course::where('name', $validated['name'])->where('status', 0)->first();

            if ($existingCourse) {
                $existingCourse->update(['status' => 1]);
                return response()->json(['message' => 'Silinmiş kurs yeniden aktif hale getirildi!', 'course' => $existingCourse], 200);
            }
        }
        $course->update($validated);
        return response()->json(['message' => 'Kurs ugurla deyisdirildi!', 'course' => $course]);
    }
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        if ($course->status == 0) {
            return response()->json(['error' => 'Bu kurs  silinib!'], 400);
        }

        $course->update(['status' => 0]);
        $course->delete();

        return response()->json(['message' => 'Kurs ugurla silindi!']);
    }
}
