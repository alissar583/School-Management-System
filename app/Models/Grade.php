<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $table = 'grades';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];
    protected $hidden = ['created_at','updated_at'
    ];


    public function class(){
        return $this->hasMany(Claass::class, 'grade_id');

    }
}
