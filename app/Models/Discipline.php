<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    protected $table = 'disciplines'; // Doğru tablo adı burada belirtilmeli
    protected $fillable = ['name', 'department_id', 'status'];
    // Disciplin Model
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id'); // Burada ilişkilerin doğru kurulduğundan emin olun
    }
}
