<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    protected $table = 'days';
    protected $hidden = ['created_at','updated_at'];

    public function lessons() {
        return $this->belongsToMany(Lesson::class, 'lesson_day', 'day_id', 'lesson_id');
    }
}
