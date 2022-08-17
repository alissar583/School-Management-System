<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    public $primaryKey = 'id';

    public $fillable = [
        'name','mark'
    ];
    protected $hidden = ['pivot','created_at','updated_at'];

    public $timestamps = true;

    public function book(){
        return $this->hasMany(Book::class, 'subject_id');

    }
    public function teacher(){
        return $this->belongsToMany(Teacher::class, 'teacher__subjects','subject_id','teacher_id');
    }
//    class & classroom for return teacher with subject & class classroom
    public function classClassroom(){
        return $this->belongsToMany(ClassClassroom::class, 'teacher__subjects','subject_id','class_classroom_id')
            ->with(['classes', 'classrooms']);
    }
    public function exam(){
        return $this->hasMany(Exam::class, 'subject_id');

    }

    public function classes() {

        return $this->belongsToMany(Claass::class, 'subject_mark','subject_id','class_id');
    }

    public function syllabi(){
        return $this->hasMany(Syllabi::class, 'subject_id');
    }

    public function mark(){
        return $this->hasMany(SubjectMark::class,'subject_id');
       }
}
