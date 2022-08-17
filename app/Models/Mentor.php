<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Mentor extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $table = 'mentors';
    public $primaryKey = 'id';

    public $fillable = [
        'email',
        'f_name',
        'l_name',
        'code',
        'address_id',
        'joining_date',
        'phone',
        'class_id',
    ];

    protected $with = ['class', 'address'];
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public function class(){
        return $this->belongsTo(Claass::class, 'class_id');

    }
    public function address(){
        return $this->belongsTo(Address::class, 'address_id');
    }
}
