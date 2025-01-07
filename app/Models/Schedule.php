<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Discipline;
use App\Models\Corps;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Group;
use App\Models\Room;
use App\Models\LessonType;
use App\Models\Hour;
use App\Models\Semester;
use App\Models\WeekType;
use App\Models\Day;
use App\Models\User;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id', 'department_id', 'group_id', 'corp_id', 'room_id',
        'lesson_type_id', 'hour_id', 'semester_id', 'week_type_id', 'day_id',
        'user_id', 'discipline_id', 'status'
    ];

    /**
     * Define relationship with Faculty model
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Define relationship with Department model
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Define relationship with Group model
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Define relationship with Corp model
     */
    public function corp()
    {
        return $this->belongsTo(Corps::class);
    }

    /**
     * Define relationship with Room model
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Define relationship with LessonType model
     */
    public function lessonType()
    {
        return $this->belongsTo(LessonType::class);
    }

    /**
     * Define relationship with Hour model
     */
    public function hour()
    {
        return $this->belongsTo(Hour::class);
    }

    /**
     * Define relationship with Semester model
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Define relationship with WeekType model
     */
    public function weekType()
    {
        return $this->belongsTo(WeekType::class);
    }

    /**
     * Define relationship with Day model
     */
    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    /**
     * Define relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define relationship with Discipline model
     */
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    /**
     * Scope query by User ID
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope query by Group ID
     */
    public function scopeByGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    /**
     * Scope query by Faculty ID
     */
    public function scopeByFaculty($query, $facultyId)
    {
        return $query->where('faculty_id', $facultyId);
    }

    /**
     * Scope query by Discipline ID
     */
    public function scopeByDiscipline($query, $disciplineId)
    {
        return $query->where('discipline_id', $disciplineId);
    }

    /**
     * Scope query by Room ID
     */
    public function scopeByRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    /**
     * Scope query by Semester ID
     */
    public function scopeBySemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    /**
     * Scope query by Status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope query by Date Range (Created At)
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope query by Week Type ID
     */
    public function scopeByWeekType($query, $weekTypeId)
    {
        return $query->where('week_type_id', $weekTypeId);
    }

    /**
     * Scope query by Day ID
     */
    public function scopeByDay($query, $dayId)
    {
        return $query->where('day_id', $dayId);
    }

    /**
     * Scope query by Hour ID
     */
    public function scopeByHour($query, $hourId)
    {
        return $query->where('hour_id', $hourId);
    }

    /**
     * Scope query by Corps ID
     */
    public function scopeByCorp($query, $corpId)
    {
        return $query->where('corp_id', $corpId);
    }
}
