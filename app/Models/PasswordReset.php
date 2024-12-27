<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'reset_code', 'reset_code_expires_at','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
