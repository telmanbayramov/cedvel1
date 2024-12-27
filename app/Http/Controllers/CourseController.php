<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Specialty;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('specialty')
            ->where('status', 1)
            ->get();

        $groupedCourses = $courses->groupBy('name')->map(function ($group) {
            $course = $group->first();

            $specialties = $group->map(function ($course) {
                return $course->specialty->name;
            })->unique();

            return [
                'id' => $course->id,
                'name' => $course->name,
                'specialities' => $specialties
            ];
        });

        return response()->json(['courses' => $groupedCourses]);
    }

    public function store(Request $request)
    {
        // Veriyi validate et
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialty_id' => 'required|array|exists:specialities,id|distinct', // 'distinct' ile duplicate'leri engelliyoruz
            'specialty_id.*' => 'exists:specialities,id',
        ]);
    
        // Her specialty_id için kursları ekle
        $courses = [];
        foreach ($validated['specialty_id'] as $specialtyId) {
            $courses[] = Course::create([
                'name' => $validated['name'],
                'specialty_id' => $specialtyId,
            ]);
        }
    
        return response()->json(['message' => 'Kurslar başarıyla eklendi!', 'courses' => $courses], 201);
    }
    
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        if ($course->status == 0) {
            return response()->json(['error' => 'Bu kurs silinib ve guncellene bilmez!'], 400);
        }
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'specialty_id' => 'sometimes|exists:specialities,id',
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
