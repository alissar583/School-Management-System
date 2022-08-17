<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionQuiz extends Model
{
    use HasFactory;
    protected $table = 'question_quizzes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name'
    ];

    public function quiz(){
        return $this->belongsTo(Quiz::class, 'exam_id');

    }
    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }

}
