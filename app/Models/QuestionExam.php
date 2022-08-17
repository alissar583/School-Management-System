<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionExam extends Model
{
    use HasFactory;
    public $primaryKey = 'id';

    public $fillable = [
       'exam_id','question_id','mark'
    ];

    public $timestamps = true;

    public function exam(){
        return $this->belongsTo(Exam::class, 'exam_id');

    }
   public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }
}
