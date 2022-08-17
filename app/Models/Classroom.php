<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $table = 'classrooms';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'max_number',
    ];
    public $timestamps = true;

    protected $hidden = ['pivot','created_at','updated_at'];


    public function class() {
        return $this->belongsToMany(Claass::class, 'claass_classrooms','classroom_id','class_id');
    }

    // public function teacherSubjects() {
    //     return $this->belongsToMany(
    //         TeacherSubject::class,
    //         'claass_classroom_teacher_subject',
    //         'c_cr_id',
    //         't_s_id'
    //     );
    // }

}
