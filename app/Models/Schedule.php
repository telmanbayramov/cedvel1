<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Discipline;
use App\Models\Corps;
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
     * Status getter (optional: for better readability)
     */
 
}
