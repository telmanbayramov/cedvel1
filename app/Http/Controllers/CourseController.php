<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Specialty;

class CourseController extends Controller
{
    public function index()
    {
        $course = Course::where('status', '1')->get();
        return response()->json($course);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

        ]);

        $courses[] = Course::create([
            'name' => $validated['name'],
        ]);

        return response()->json(['message' => 'Kurs başarıyla eklendi!', 'courses' => $courses], 201);
    }
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        if ($course->status == 0) {
            return response()->json(['error' => 'Bu kurs silinmiş ve güncellenemez!'], 400);
        }
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',

        ]);
        if (isset($validated['name'])) {
            $existingCourse = Course::where('name', $validated['name'])->where('status', 0)->first();

            if ($existingCourse) {
                $existingCourse->update(['status' => 1]);
                return response()->json(['message' => 'Silinmiş kurs yeniden aktif hale getirildi!', 'course' => $existingCourse], 200);
            }
        }
        $course->update($validated);
        return response()->json(['message' => 'Kurs başarıyla güncellendi!', 'course' => $course]);
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
