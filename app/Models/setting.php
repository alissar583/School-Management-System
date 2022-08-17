<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class setting extends Model
{
    use HasFactory;
    public $table = 'settings';

    public $primaryKey = 'id';

    public $fillable = [
        'name', 'address_id', 'admin_id','logo','phone'
    ];

    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = true;

    public function address(){
        return $this->belongsTo(Address::class, 'address_id');
            //->whereDate('expired_date', '>=', now());

    }
    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
