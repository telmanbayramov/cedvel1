<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Redis;
use App\Helpers\GroupHelper;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\Specialty;

class GroupController extends Controller
{
    public function index()
{
    $groups = Group::where('status', '1')->with(['faculty', 'speciality', 'course'])->get();

    $groups = $groups->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'student_amount' => $item->student_amount,
            'course_name' => $item->course->name ?? null,  // Burada course_id yerine course_name kullanılıyor
            'group_type_label' => GroupHelper::getGroupTypeName($item->group_type),
            'group_level_label' => GroupHelper::getGroupLevelName($item->group_level),
            'faculty_name' => $item->faculty->name ?? null,
            'speciality_name' => $item->speciality->name ?? null,
        ];
    });

    return response()->json($groups, 200);
}

public function show($id)
{
    $group = Group::where('status', '1')
        ->with(['faculty', 'speciality', 'course'])  // course ilişkisinin dahil edilmesi
        ->findOrFail($id);
    
    $formattedGroup = [
        'id' => $group->id,
        'name' => $group->name,
        'student_amount' => $group->student_amount,
        'course_name' => $group->course->name ?? null,  
        'group_type_label' => GroupHelper::getGroupTypeName($group->group_type),
        'group_level_label' => GroupHelper::getGroupLevelName($group->group_level),
        'faculty_name' => $group->faculty->name ?? null,
        'speciality_name' => $group->speciality->name ?? null,
    ];

    return response()->json($formattedGroup, 200);
}
public function store(Request $request)
{
    // Gelen veriyi doğrulama
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'student_amount' => 'required|integer|min:1,max:255',
        'group_type' => 'required|integer|in:1,2', // group_type'ı sayısal (1 veya 2) olarak doğruluyoruz
        'faculty_name' => 'required|string',
        'course_name' => 'required|string',
        'speciality_name' => 'required|string',
        'group_level' => 'required|integer|in:1,2', // group_level'ı sayısal (1 veya 2) olarak doğruluyoruz
    ]);

    // İlgili faculty, course ve specialty'yi al
    $faculty = Faculty::where('name', $validated['faculty_name'])->first();
    $course = Course::where('name', $validated['course_name'])->first();
    $specialty = Specialty::where('name', $validated['speciality_name'])->first();

    // Eğer herhangi bir ilişki bulunamazsa, uygun hata mesajı döndür
    if (!$faculty || !$course || !$specialty) {
        return response()->json([
            'error' => 'Faculty, Course, or Specialty not found.'
        ], 400); // 400 Bad Request döndür
    }

    // Yeni grup oluşturma
    $group = Group::create([
        'name' => $validated['name'],
        'student_amount' => $validated['student_amount'],
        'group_type' => $validated['group_type'], // Sayısal group_type'ı doğrudan kullanıyoruz
        'faculty_id' => $faculty->id,  // Bulunan faculty ID'si
        'course_id' => $course->id,    // Bulunan course ID'si
        'speciality_id' => $specialty->id,  // Bulunan specialty ID'si
        'group_level' => $validated['group_level'], // Sayısal group_level'ı doğrudan kullanıyoruz
    ]);

    // API cevabı olarak oluşturulan grubun bilgilerini döndür
    return response()->json([
        'name' => $group->name,
        'student_amount' => $group->student_amount,
        'group_type' => $group->group_type, // Sayısal group_type'ı döndürüyoruz
        'faculty_name' => $faculty->name,
        'course_name' => $course->name,
        'speciality_name' => $specialty->name,
        'group_level' => $group->group_level,  // Sayısal group_level'ı döndürüyoruz
    ], 201);
}

public function getGroupInfo()
{
    // Grup türleri için gerekli verileri al
    $groupTypes = [
        '1' => GroupHelper::getGroupTypeName(1),  // 1: Əyani
        '2' => GroupHelper::getGroupTypeName(2),  // 2: Qiyabi
    ];

    // Grup seviyeleri için gerekli verileri al
    $groupLevels = [
        '1' => GroupHelper::getGroupLevelName(1),   // 1: Magistr
        '2' => GroupHelper::getGroupLevelName(2),   // 2: Bakalavr
    ];

    // Her iki veriyi de aynı JSON response içinde döndürüyoruz
    return response()->json([
        'group_types' => $groupTypes,
        'group_levels' => $groupLevels,
    ]);
}

public function update(Request $request, $id)
{
    // Mevcut grubu ID'ye göre bul
    $group = Group::findOrFail($id);
 
    // Validasyon işlemi
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'student_amount' => 'required|integer|min:1',
        'group_type' => 'required|integer',
        'faculty_name' => 'required|string',
        'course_name' => 'required|string',
        'speciality_name' => 'required|string',
        'group_level' => 'required|integer',
    ]);
 
    // İlgili faculty, course ve specialty'yi al
    $faculty = Faculty::where('name', $validated['faculty_name'])->first();
    $course = Course::where('name', $validated['course_name'])->first();
    $specialty = Specialty::where('name', $validated['speciality_name'])->first();
 
    // Grubu güncelle
    $group->update([
        'name' => $validated['name'],
        'student_amount' => $validated['student_amount'],
        'group_type' => $validated['group_type'],
        'faculty_id' => $faculty->id,
        'course_id' => $course->id,
        'speciality_id' => $specialty->id,
        'group_level' => $validated['group_level'],
    ]);
 
    // Güncellenmiş grup bilgilerini döndür
    return response()->json([
        'id' => $group->id,
        'name' => $group->name,
        'student_amount' => $group->student_amount,
        'group_type' => $group->group_type,
        'faculty_name' => $faculty->name,
        'course_name' => $course->name,
        'speciality_name' => $specialty->name,
        'group_level' => $group->group_level,
    ]);
}

    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->update(['status' => '0']);
        return response()->json(['message' => 'qrup uğurla deaktiv edildi']);
    }
    
}
