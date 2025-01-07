<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Group;
use App\Models\Corps;
use App\Models\Room;
use App\Models\LessonType;
use App\Models\Hour;
use App\Models\Semester;
use App\Models\WeekType;
use App\Models\Day;
use App\Models\User;
use App\Models\Discipline;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $facultyId = $request->query('faculty_id');
        $departmentId = $request->query('department_id');
        $groupId = $request->query('group_id');
        $corpId = $request->query('corp_id');
        $roomId = $request->query('room_id');
        $lessonTypeId = $request->query('lesson_type_id');
        $hourId = $request->query('hour_id');
        $semesterId = $request->query('semester_id');
        $weekTypeId = $request->query('week_type_id');
        $dayId = $request->query('day_id');
        $userId = $request->query('user_id');
        $disciplineId = $request->query('discipline_id');

        $schedulesQuery = Schedule::where('status', '1')->with([
            'faculty',
            'department',
            'group',
            'corp',
            'room',
            'lessonType',
            'hour',
            'semester',
            'weekType',
            'day',
            'user',
            'discipline',
        ]);

        if ($facultyId) {
            $schedulesQuery->where('faculty_id', $facultyId);
        }

        if ($departmentId) {
            $schedulesQuery->where('department_id', $departmentId);
        }

        if ($groupId) {
            $schedulesQuery->where('group_id', $groupId);
        }

        if ($corpId) {
            $schedulesQuery->where('corp_id', $corpId);
        }

        if ($roomId) {
            $schedulesQuery->where('room_id', $roomId);
        }

        if ($lessonTypeId) {
            $schedulesQuery->where('lesson_type_id', $lessonTypeId);
        }

        if ($hourId) {
            $schedulesQuery->where('hour_id', $hourId);
        }

        if ($semesterId) {
            $schedulesQuery->where('semester_id', $semesterId);
        }

        if ($weekTypeId) {
            $schedulesQuery->where('week_type_id', $weekTypeId);
        }

        if ($dayId) {
            $schedulesQuery->where('day_id', $dayId);
        }

        if ($userId) {
            $schedulesQuery->where('user_id', $userId);
        }

        if ($disciplineId) {
            $schedulesQuery->where('discipline_id', $disciplineId);
        }

        $schedules = $schedulesQuery->get();

        $groupedSchedules = $schedules->groupBy('group_id')->map(function ($groupSchedules) {
            $firstSchedule = $groupSchedules->first();

            return [
                'id' => $firstSchedule->id,
                'group_name' => $firstSchedule->group ? $firstSchedule->group->name : null,
                'faculty_name' => $firstSchedule->faculty ? $firstSchedule->faculty->name : null,
                'department_name' => $firstSchedule->department ? $firstSchedule->department->name : null,
                'lessons' => $groupSchedules->map(function ($schedule) {
                    return [
                        'schedule_id' => $schedule->id,
                        'day_name' => $schedule->day ? $schedule->day->name : null,
                        'hour_name' => $schedule->hour ? $schedule->hour->name : null,
                        'discipline_name' => $schedule->discipline ? $schedule->discipline->name : null,
                        'user_name' => $schedule->user ? $schedule->user->name : null,
                        'corp_name' => $schedule->corp ? $schedule->corp->name : null,
                        'lesson_type_name' => $schedule->lessonType ? $schedule->lessonType->name : null,
                        'room_name' => $schedule->room ? $schedule->room->name : null,
                        'year' => $schedule->semester ? $schedule->semester->year : null,
                        'semester_num' => $schedule->semester ? $schedule->semester->semester_num : null,
                        'week_type_name' => $schedule->weekType ? $schedule->weekType->name : null,
                    ];
                }),
            ];
        });

        return response()->json(['schedules' => $groupedSchedules]);
    }

    public function show($id)
    {
        $schedule = Schedule::where('status', '1')->with([
            'faculty',
            'department',
            'group',
            'corp',
            'room',
            'lessonType',
            'hour',
            'semester',
            'weekType',
            'day',
            'user',
            'discipline',
        ])->find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        $formattedSchedule = [
            'id' => $schedule->id,
            'faculty_name' => $schedule->faculty ? $schedule->faculty->name : null,
            'department_name' => $schedule->department ? $schedule->department->name : null,
            'group_name' => $schedule->group ? $schedule->group->name : null,
            'corp_name' => $schedule->corp ? $schedule->corp->name : null,
            'room_name' => $schedule->room ? $schedule->room->name : null,
            'lesson_type_name' => $schedule->lessonType ? $schedule->lessonType->name : null,
            'hour_name' => $schedule->hour ? $schedule->hour->name : null,
            'year' => $schedule->semester ? $schedule->semester->year : null,
            'semester_num' => $schedule->semester ? $schedule->semester->semester_num : null,
            'week_type_name' => $schedule->weekType ? $schedule->weekType->name : null,
            'day_name' => $schedule->day ? $schedule->day->name : null,
            'user_name' => $schedule->user ? $schedule->user->name : null,
            'discipline_name' => $schedule->discipline ? $schedule->discipline->name : null,
        ];

        return response()->json(['schedule' => $formattedSchedule]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => 'required|integer|exists:faculties,id',
            'department_id' => 'required|integer|exists:departments,id',
            'group_id' => 'required|integer|exists:groups,id',
            'corp_id' => 'required|integer|exists:corps,id',
            'room_id' => 'required|integer|exists:rooms,id',
            'lesson_type_id' => 'required|integer|exists:lesson_types,id',
            'hour_id' => 'required|integer|exists:hours,id',
            'semester_id' => 'required|integer|exists:semesters,id',
            'week_type_id' => 'nullable|integer|exists:week_types,id',
            'day_id' => 'required|integer|exists:days,id',
            'user_id' => 'required|integer|exists:users,id',
            'discipline_id' => 'required|integer|exists:disciplines,id',
        ]);

        $schedule = Schedule::create([
            'faculty_id' => $validated['faculty_id'],
            'department_id' => $validated['department_id'],
            'group_id' => $validated['group_id'],
            'corp_id' => $validated['corp_id'],
            'room_id' => $validated['room_id'],
            'lesson_type_id' => $validated['lesson_type_id'],
            'hour_id' => $validated['hour_id'],
            'semester_id' => $validated['semester_id'],
            'week_type_id' => $validated['week_type_id'],
            'day_id' => $validated['day_id'],
            'user_id' => $validated['user_id'],
            'discipline_id' => $validated['discipline_id'],
        ]);

        return response()->json(['message' => 'Schedule created successfully', 'data' => $schedule]);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'faculty_id' => 'required|integer|exists:faculties,id',
            'department_id' => 'required|integer|exists:departments,id',
            'group_id' => 'required|integer|exists:groups,id',
            'corp_id' => 'required|integer|exists:corps,id',
            'room_id' => 'required|integer|exists:rooms,id',
            'lesson_type_id' => 'required|integer|exists:lesson_types,id',
            'hour_id' => 'required|integer|exists:hours,id',
            'semester_id' => 'required|integer|exists:semesters,id',
            'week_type_id' => 'required|integer|exists:week_types,id',
            'day_id' => 'required|integer|exists:days,id',
            'user_id' => 'required|integer|exists:users,id',
            'discipline_id' => 'required|integer|exists:disciplines,id',
        ]);

        $schedule = Schedule::findOrFail($id);

        $schedule->update([
            'faculty_id' => $validated['faculty_id'],
            'department_id' => $validated['department_id'],
            'group_id' => $validated['group_id'],
            'corp_id' => $validated['corp_id'],
            'room_id' => $validated['room_id'],
            'lesson_type_id' => $validated['lesson_type_id'],
            'hour_id' => $validated['hour_id'],
            'semester_id' => $validated['semester_id'],
            'week_type_id' => $validated['week_type_id'],
            'day_id' => $validated['day_id'],
            'user_id' => $validated['user_id'],
            'discipline_id' => $validated['discipline_id'],
        ]);

        return response()->json(['message' => 'Schedule updated successfully', 'data' => $schedule]);
    }
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->update(['status' => '0']);

        return response()->json(['message' => 'Schedule deleted successfully']);
    }
}
