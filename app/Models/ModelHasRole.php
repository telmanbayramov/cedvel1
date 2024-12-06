<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    use HasFactory;

    // Tablo adı
    protected $table = 'model_has_roles';

    // Birincil anahtar kullanmadığı için primary key'i belirtmeyin
    public $incrementing = false;

    // Timestamps kullanılmadığı için devre dışı bırakın
    public $timestamps = false;

    // Doldurulabilir alanlar
    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];

    // İlişkiler
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

}
