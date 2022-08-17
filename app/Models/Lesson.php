<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
       protected $fillable = [
        'name'
    ];
    protected $table = 'lessons';
    protected $hidden = ['created_at','updated_at'];

    public function days() {
        return $this->belongsToMany(Day::class, 'lesson_day', 'lesson_id', 'day_id');
    }
}
