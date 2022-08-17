<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineClass extends Model
{
    use HasFactory;
    protected $table = 'online_classes';
    public $primaryKey = 'id';

    public $fillable = [
     'link','teacher_subject_id','date'
    ];
    protected $hidden = ['created_at','updated_at'
    ];
    public $timestamps = true;

    public function teacherSubject(){
        return $this->belongsTo(TeacherSubject::class,'teacher_subject_id');
    }

}
