<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonDay extends Model
{
    use HasFactory;

    protected $table = 'lesson_day';
    protected $hidden = ['created_at','updated_at'];

    public function lessons(){
        return $this->belongsTo(Lesson::class,'lesson_id');
    }
    public function days(){
        return $this->belongsTo(Day::class,'day_id');
    }
}
