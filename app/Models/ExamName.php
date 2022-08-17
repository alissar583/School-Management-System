<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamName extends Model
{
    use HasFactory;

    public $primaryKey = 'id';

    public $fillable = [
       'name'
    ];

    public $timestamps = true;

    public function Exam(){
        return $this->hasMany(Exam::class, 'exam_name_id');

    }

}
