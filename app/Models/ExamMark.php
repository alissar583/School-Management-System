<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamMark extends Model
{
    use HasFactory;

    protected $table = 'exam_marks';

    protected $primaryKey = 'id';
    protected $fillable = [
        'exam_id', 'student_id', 'mark'
    ];
    protected $hidden = [
        'pivot', 'created_at','updated_at'
    ];


}
