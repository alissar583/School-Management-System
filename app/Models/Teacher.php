<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $table = 'teachers';

    public $primaryKey = 'id';
    public $fillable = [
        'address_id',
        'religion_id',
        'gender_id',
        'f_name',
        'l_name',
        'email',
        'code',
        'joining_date',
        'salary',
        'picture'
    ];
    protected $hidden = ['pivot','created_at','updated_at'];
    public $timestamps = true;

// teacher with subjects with class& classroom
    public function subject(){
        return $this->belongsToMany(Subject::class,'teacher__subjects',
                'teacher_id',
                'subject_id'
            );
    }
//
    public function subjects(){
        return $this->belongsToMany(Subject::class,'teacher__subjects',
                'teacher_id',
                'subject_id'
            );
    }

   public function address(){
        return $this->belongsTo(Address::class, 'address_id');
    }
    public function religion(){
        return $this->belongsTo(Religtion::class, 'religion_id');
    }

    public function gender(){
        return $this->belongsTo(Gender::class, 'gender_id');
    }


//    public function classes() {
//        return $this->belongsToMany(Claass::class, 'subject_class','teacher_id','class_id');
//
//    }
//    public function classClassroom() {
//        return $this->belongsToMany(
//            ClassClassroom::class,
//            'teacher__subjects',
//            'teacher_id',
//            'class_classroom_id'
//        )->with(['classes', 'classrooms','subjects']);
//    }
}
