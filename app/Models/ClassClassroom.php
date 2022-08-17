<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassClassroom extends Model
{
    use HasFactory;

    protected $table = 'claass_classrooms';
    protected $primaryKey = 'id';
    protected $fillable = [
        'class_id',
        'classroom_id',
        'teacher_id'
    ];
    protected $hidden = ['pivot', 'created_at','updated_at'];
    public $timestamps = true;

    public function teachers() {
        return $this->belongsToMany(Teacher::class, 'teacher_classclassroom','claass_classroom_id','teacher_id');
    }

    public function teacher() {
        return $this->belongsToMany(Teacher::class, 'teacher__subjects','class_classroom_id','teacher_id');
    }


    public function classes() {
        return $this->belongsTo(Claass::class, 'class_id');
    }

    public function classrooms() {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function students() {
        return $this->hasMany(Student::class, 'class_classroom_id');
    }

}
