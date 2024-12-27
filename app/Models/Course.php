<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'specialty_id', 'status',
    ];
      public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
