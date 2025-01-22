<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable=['name','student_amount','group_type','faculty_id','course_id','speciality_id','group_level','status'];
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
    public function speciality()
    {
        return $this->belongsTo(Specialty::class, 'speciality_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class);  // Group modeli Course modeline bağlıdır
    }
    
}
