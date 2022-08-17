<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $table = 'quizzes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'mark' ,
        'quiz_name_id',
        'teacher_subject_id',
        'season_id',
        'start',
        'end'
        ];

    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    public function questions() {
        return $this->belongsToMany(
            Question::class,
            'question_quizzes',
            'quiz_id',
            'question_id'
        )->with('choices');
    }

    public function quizName() {
        return $this->belongsTo(Q::class);
    }
    public function teacherAndSubject() {
        return $this->belongsTo(TeacherSubject::class, 'teacher_subject_id')->with('subjects');
    }
}
