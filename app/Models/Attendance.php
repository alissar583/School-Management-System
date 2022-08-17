<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    public $primaryKey = 'id';

    protected $table = 'attendances';
    public $fillable = [
        'date'
    ];
    protected $hidden = ['pivot'];
    public $timestamps = false;


//    public function student(){
//        return $this->belongsTo(Student::class, 'student_id');
//    }
    public function student(){
        return $this->belongsToMany(Student::class, 'attendances_students', 'attendance_id', 'student_id');
    }

}
