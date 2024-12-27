<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable=['name','room_capacity','department_id','room_type_id','corps_id','status'];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // RoomType ilişkisini tanımla
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    // Corps ilişkisini tanımla
    public function corps()
    {
        return $this->belongsTo(Corps::class);
    }
}
