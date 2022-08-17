<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claass extends Model
{
    use HasFactory;
    protected $table = 'claasses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'grade_id'
    ];
    protected $hidden = ['pivot', 'created_at', 'updated_at'];
    public $timestamps = true;

    public function grade() {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function Exam(){
        return $this->hasMany(Exam::class, 'class_id');
    }
    public function subjects() {
        return $this->belongsToMany(Subject::class, 'subject_mark','class_id','subject_id');
    }

    public function classroom() {
        return $this->belongsToMany(Classroom::class, 'claass_classrooms','class_id','classroom_id');
    }


    public function syllabi(){
        return $this->hasMany(Syllabi::class, 'class_id');

    }

    public function mark(){
        return $this->hasMany(SubjectMark::class,'class_id');
    }
}
