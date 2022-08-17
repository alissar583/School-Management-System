<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectMark extends Model
{
    use HasFactory;


    public $table= 'subject_mark';
    public $primaryKey = 'id';

    public $fillable = [
        'subject_id', 'class_id','mark'
    ];

    public $hidden = ['created_at','updated_at'];
    public $timestamps = true;

   public function subject(){
    return $this->belongsTo(Subject::class,'subject_id');
   }

   public function class(){
    return $this->belongsTo(Claass::class,'class_id');
   }

   public function exam(){
    return $this->hasMany(Exam::class,'subject_mark_id');
   }

}
