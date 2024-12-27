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
        // Veritabanından schedule bilgilerini alıyoruz
        $schedules = Schedule::where('status', '1')->with([
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
            'user', // Öğretmen bilgisi
            'discipline',
        ])->get();

        // Gruplara göre verileri düzenle
        $groupedSchedules = $schedules->groupBy('group_id')->map(function ($groupSchedules) {
            // Grup bilgilerini al
            $firstSchedule = $groupSchedules->first();

            return [
                'id' => $firstSchedule->id,
                'group_name' => $firstSchedule->group ? $firstSchedule->group->name : null,
                'faculty_name' => $firstSchedule->faculty ? $firstSchedule->faculty->name : null, // Faculty
                'department_name' => $firstSchedule->department ? $firstSchedule->department->name : null, // Department
                'lessons' => $groupSchedules->map(function ($schedule) {
                    return [
                        'schedule_id' => $schedule->id, // Her dersin ID'sini ekledik
                        'day_name' => $schedule->day ? $schedule->day->name : null,
                        'hour_name' => $schedule->hour ? $schedule->hour->name : null,
                        'discipline_name' => $schedule->discipline ? $schedule->discipline->name : null,
                        'user_name' => $schedule->user ? $schedule->user->name : null, // Öğretmen adı
                        'corp_name' => $schedule->corp ? $schedule->corp->name : null, // Corp adı
                        'lesson_type_name' => $schedule->lessonType ? $schedule->lessonType->name : null, // Lesson type adı
                        'room_name' => $schedule->room ? $schedule->room->name : null, // Room adı
                        'year' => $schedule->semester ? $schedule->semester->year : null,
                        'semester_num' => $schedule->semester ? $schedule->semester->semester_num : null,
                        'week_type_name' => $schedule->weekType ? $schedule->weekType->name : null, // Week type adı
                    ];
                }),
            ];
        });

        return response()->json(['schedules' => $groupedSchedules]);
    }


    public function show($id)
    {
        // Retrieve the schedule by ID with the related models
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

        // Check if schedule exists
        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        // Format the schedule with names instead of IDs
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
            'week_type_id' => 'nullable|integer|exists:week_types,id', // nullable ekledik
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
            'week_type_id' => $validated['week_type_id'], // Burada null olabilir
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
