<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    public $primaryKey = 'id';

    public $fillable = [
       'mark',
       'subject_mark_id',
       'exam_name_id',
       'season_id',
       'start',
        'end',
        'active'
    ];

    public $timestamps = true;
    protected $hidden = [
        'created_at','updated_at'
    ];


    public function subjectMark(){
        return $this->belongsTo(SubjectMark::class, 'subject_mark_id');

    }

    public function season(){
        return $this->belongsTo(Season::class, 'season_id');

    }

    public function name(){
        return $this->belongsTo(ExamName::class, 'exam_name_id');

    }

    public function questionExam(){
        return $this->hasMany(QuestionExam::class, 'exam_id');

    }
    public function questions() {
        return $this->belongsToMany(
            Question::class,
            'question_exams',
            'exam_id',
            'question_id'
        )->with('choices');
    }
}
