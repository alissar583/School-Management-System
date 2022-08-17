<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\MockObject\Builder\Stub;

class Nationality extends Model
{
    use HasFactory;
    protected $table = 'nationality';
    protected $fillable = [
        'name'
    ];
    protected $hidden = ['created_at','updated_at'
    ];
    protected $timestamp = true;

    public function students()
    {
         return $this->hasMany(Student::class,'nationality_id');
    }
}
