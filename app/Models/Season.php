<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;
    public $fillable =[
        'name'
    ];
    public $timestamps = true;

    public function Exam(){
        return $this->hasMany(Exam::class,'season_id');
    }

    public function Quize(){
        return $this->hasMany(Quiz::class,'season_id');
    }

}
