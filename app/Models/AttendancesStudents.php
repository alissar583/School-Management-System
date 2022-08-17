<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendancesStudents extends Model
{
    use HasFactory;

    protected $table = 'attendances_students';

    public function attendance() {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }
    public function status() {
        return $this->belongsTo(AttendancesStatus::class, 'status_id');
    }
}
