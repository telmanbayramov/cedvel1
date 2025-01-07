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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialty_id' => 'required|array|exists:specialities,id|distinct', 
            'specialty_id.*' => 'exists:specialities,id',
        ]);
    
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
        // Kursu bulma
        $course = Course::findOrFail($id);
    
        // Eğer kurs silinmişse güncellenmesine izin verme
        if ($course->status == 0) {
            return response()->json(['error' => 'Bu kurs silinmiş ve güncellenemez!'], 400);
        }
    
        // Verilen veri için doğrulama
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'specialty_id' => 'sometimes|array', // specialty_id'nin bir dizi olmasını sağla
            'specialty_id.*' => 'exists:specialities,id', // Her bir specialty_id'nin geçerli bir id olmasını kontrol et
        ]);
    
        // Kurs adı güncellenmişse, başka bir silinmiş kursun yeniden aktif edilip edilmediğine bak
        if (isset($validated['name'])) {
            $existingCourse = Course::where('name', $validated['name'])->where('status', 0)->first();
    
            if ($existingCourse) {
                // Silinmiş kursu tekrar aktif hale getir
                $existingCourse->update(['status' => 1]);
                return response()->json(['message' => 'Silinmiş kurs yeniden aktif hale getirildi!', 'course' => $existingCourse], 200);
            }
        }
    
        // Kursu güncelle
        $course->update($validated);
    
        // Eğer specialty_id array ise, ilişkili veriyi güncelle
        if (isset($validated['specialty_id'])) {
            // Burada specialty_id'yi bağlı olduğunuz modele göre işleyebilirsiniz
            // Örneğin, bir kursun birden fazla specialty ile ilişkisi varsa:
            $course->specialties()->sync($validated['specialty_id']); // sync() fonksiyonu, ilişkili tabloları günceller.
        }
    
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
