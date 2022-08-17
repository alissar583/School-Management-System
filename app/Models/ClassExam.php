<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassExam extends Model
{
    use HasFactory;
    public $primaryKey = 'id';

    public $fillable = [
       'class_id','exam_id'
    ];
    protected $hidden = [
        'created_at','updated_at'
    ];
    public $timestamps = true;

    public function class(){
        return $this->belongsTo(Claass::class, 'class_id');

    }
   public function exam(){
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
