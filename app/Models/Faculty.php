<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Specialty;

class Faculty extends Model
{
    protected $fillable = ['name', 'status'];
    public function specialities()
    {
        return $this->hasMany(Specialty::class, 'faculty_id', 'id')->where('status', 1);
    }
    public function departments()
    {
        return $this->hasMany(Department::class)->where('status','1'); 
    }
    public function groups()
    {
        return $this->hasMany(Group::class)->where('status','1');
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'faculty_id', 'id')->where('status','1');
    }
}
