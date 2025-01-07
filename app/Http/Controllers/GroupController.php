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
                'course_name' => $item->course->name ?? null,
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
            ->with(['faculty', 'speciality', 'course'])
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_amount' => 'required|integer|min:1,max:255',
            'group_type' => 'required|integer|in:1,2',
            'faculty_name' => 'required|string',
            'course_name' => 'required|string',
            'speciality_name' => 'required|string',
            'group_level' => 'required|integer|in:1,2',
        ]);

        $faculty = Faculty::where('name', $validated['faculty_name'])->first();
        $course = Course::where('name', $validated['course_name'])->first();
        $specialty = Specialty::where('name', $validated['speciality_name'])->first();

        if (!$faculty || !$course || !$specialty) {
            return response()->json([
                'error' => 'Faculty, Course, or Specialty not found.'
            ], 400);
        }

        $group = Group::create([
            'name' => $validated['name'],
            'student_amount' => $validated['student_amount'],
            'group_type' => $validated['group_type'],
            'faculty_id' => $faculty->id,
            'course_id' => $course->id,
            'speciality_id' => $specialty->id,
            'group_level' => $validated['group_level'],
        ]);

        return response()->json([
            'name' => $group->name,
            'student_amount' => $group->student_amount,
            'group_type' => $group->group_type,
            'faculty_name' => $faculty->name,
            'course_name' => $course->name,
            'speciality_name' => $specialty->name,
            'group_level' => $group->group_level,
        ], 201);
    }

    public function getGroupInfo()
    {
        $groupTypes = [
            '1' => GroupHelper::getGroupTypeName(1),
            '2' => GroupHelper::getGroupTypeName(2),
        ];

        $groupLevels = [
            '1' => GroupHelper::getGroupLevelName(1),
            '2' => GroupHelper::getGroupLevelName(2),
        ];

        return response()->json([
            'group_types' => $groupTypes,
            'group_levels' => $groupLevels,
        ]);
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_amount' => 'required|integer|min:1',
            'group_type' => 'required|integer',
            'faculty_name' => 'required|string',
            'course_name' => 'required|string',
            'speciality_name' => 'required|string',
            'group_level' => 'required|integer',
        ]);

        $faculty = Faculty::where('name', $validated['faculty_name'])->first();
        $course = Course::where('name', $validated['course_name'])->first();
        $specialty = Specialty::where('name', $validated['speciality_name'])->first();

        $group->update([
            'name' => $validated['name'],
            'student_amount' => $validated['student_amount'],
            'group_type' => $validated['group_type'],
            'faculty_id' => $faculty->id,
            'course_id' => $course->id,
            'speciality_id' => $specialty->id,
            'group_level' => $validated['group_level'],
        ]);

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
