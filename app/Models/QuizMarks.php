<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizMarks extends Model
{
    use HasFactory;

    protected $table = 'quiz_marks';

    protected $primaryKey = 'id';
    protected $fillable = [
        'quiz_id', 'student_id', 'mark'
    ];
}
