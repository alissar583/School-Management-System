<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabi extends Model
{
    use HasFactory;


    protected $table = 'syllabi';
    protected $fillable  = [
        'content' ,
        'class_id',
        'subject_id',
        'active'
    ];
    protected $with = ['subject', 'class'];
    public $timestamps = true;
    protected $hidden = ['created_at','updated_at'];
    protected $primaryKey = 'id';

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function class() {
        return $this->belongsTo(Claass::class, 'class_id');
    }
}
