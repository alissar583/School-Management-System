<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class TimeTable extends Model
{
    use HasFactory;
    protected $table = 'time_tables';
    protected $primaryKey = 'id';

    protected $fillable  = [
        'lessonDay_id',
        'teacher_id',
        'classClassroom_id',
        'subject_id'
    ];
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = true;

    public function teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id');
    }
    public function subject(){
        return $this->belongsTo(Subject::class,'subject_id');
    }
    public function lesson(){
        return $this->belongsTo(LessonDay::class,'lessonDay_id');

    }   public function classroom(){
    return $this->belongsTo(ClassClassroom::class,'classClassroom_id');
}

}
