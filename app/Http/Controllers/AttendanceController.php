<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassClassroom;
use App\Models\Mentor;
use App\Models\Student;
use App\Traits\basicFunctionsTrait;
use App\Traits\generalTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    use basicFunctionsTrait, generalTrait;

    public function store(Request $request) {
        $checkDate = Attendance::query()->where('date', $request->date)->first();
        if (!isset($checkDate)) {
            $checkDate = Attendance::query()->create([
                'date' => $request->date
            ]);
        }
        foreach ($request->students as $student) {
            $check  = DB::table('attendances_students')
                ->where('student_id', $student['student_id'])
                ->where('attendance_id', $checkDate->id)->first();
            if (isset($check)) {
                DB::table('attendances_students')->where('id', $check->id)->update([
                    'status_id' => $student['status_id']
                ]);
            }
            if (!isset($check)) {
                DB::table('attendances_students')->insert([
                    'student_id' => $student['student_id'],
                    'status_id' => $student['status_id'],
                    'attendance_id' => $checkDate->id
                ]);
            }

        }
        return $this->returnSuccessMessage('success');
    }

    public function getAttendance(Request $request) {
        $classClassroomId = $this->checkClassClassroom($request->class_id, $request->classroom_id);
        if (! isset($classClassroomId)) {
            return $this->returnErrorMessage('input error', 400);
        }

        $data = Attendance::query()->with('student', function ($query) use ($classClassroomId)  {
            $query->with('attendances')->where('class_classroom_id', $classClassroomId->id);
        })->get();
        return $this->returnAllData('data', $data, 'success');
    }
    public function getAttendanceStudent(Student $student) {
        return $this->returnData('data', $student->load('attendances'), 'success');
    }
}
