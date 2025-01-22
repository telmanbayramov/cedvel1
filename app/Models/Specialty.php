<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;
    protected $table = 'specialities';
    protected $fillable = ['name', 'faculty_id', 'status'];
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }
    public function groups()
    {
        // faculty_id üzerinden ilişkiyi kurarak grupları filtreleriz
        return $this->hasMany(Group::class, 'speciality_id', 'id')
            ->where('status', '1'); // yalnızca aktif grupları al
    }
    
}
