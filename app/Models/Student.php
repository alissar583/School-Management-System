<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $fillable = [
        'f_name',
        'l_name',
        'email',
        'code',
        'picture',
        'birthdate',
        'parent_id',
        'blood_id',
        'gender_id',
        'religion_id',
        'nationality_id',
        'grade_id',
        'class_classroom_id',
        'academic_year_id',
        'address_id'
    ];

    protected $hidden = ['pivot','created_at','updated_at','deleted_at'
     ];

    protected $with = [
        'parent',
        'academic_year',
        'grade',
        'classClassroom',
        'address',
        'blood',
        'religion',
        'gender',
        'nationality'
    ];

    public function grade(){
        return $this->belongsTo(Grade::class, 'grade_id');
    }
//    public function claass(){
//        return $this->belongsTo(Claass::class, 'class_id');
//    }
//    public function classroom(){
//        return $this->belongsTo(Classroom::class, 'classroom_id');
//    }

    public function classClassroom() {
        return $this->belongsTo(ClassClassroom::class, 'class_classroom_id')->with(['classes', 'classrooms']);
    }
    public function academic_year(){
        return $this->belongsTo(Academic_year::class, 'academic_year_id');
    }

    public function address(){
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function parent(){
        return $this->belongsTo(Paarent::class, 'parent_id');
    }

    public function blood(){
        return $this->belongsTo(Blood::class, 'blood_id');
    }

    public function religion(){
        return $this->belongsTo(Religtion::class, 'religion_id');
    }


    public function gender(){
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function nationality(){
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }
//    public function attendance(){
//        return $this->belongsToMany(Attendance::class, 'attendances_students', 'student_id', 'attendance_id');
//    }

    public function attendances(){
        return $this->hasMany(AttendancesStudents::class, 'student_id')->with('status', 'attendance');
    }

    public function fees_invoice(){
        return $this->hasMany(Fees_Invoices::class, 'student_id');

    }

    public function quizzes() {
        return $this->belongsToMany(
            Quiz::class,
            'quiz_marks',
            'student_id',
            'quiz_id');
    }


}
