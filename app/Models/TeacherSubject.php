<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    use HasFactory;

    protected $table = 'teacher__subjects';
    protected $primaryKey = 'id';
    protected $hidden = ['pivot', 'created_at', 'updated_at'];
    public function teachers() {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
   public function subjects() {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function classClassrooms() {
        return $this->belongsTo(ClassClassroom::class, 'class_classroom_id');
    }

    public function questions() {
        return $this->hasMany(Question::class, 'teacher_subjects_id');
    }

}
