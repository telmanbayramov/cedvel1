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
        return $this->hasMany(Specialty::class, 'faculty_id', 'id'); 
    }
}
