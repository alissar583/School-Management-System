<?php

namespace App\Http\Controllers;

use App\Models\Claass;
use App\Models\ClassClassroom;
use App\Models\Classroom;
use App\Models\Day;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Mentor;
use App\Models\Teacher;
use App\Models\TimeTable;
use App\Traits\generalTrait;
use Dflydev\DotAccessData\Data;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder\Class_;

class TimeTableController extends Controller
{
    use generalTrait;
    public function store(Request $request)
    {
        $days = $request->day_id;
        $class = $request->class_id;

        foreach ($days as $day){
                foreach ($day['classroom_id'] as $classroom){
                    foreach ($classroom['lesson_id'] as $lesson){

                        $lessonDay = DB::table('lesson_day')
                            ->where('lesson_id',$lesson['id'])
                            ->where('day_id',$day['id'])
                            ->first();

                        $classClassroom = DB::table('claass_classrooms')
                            ->where('class_id',$class)
                            ->where('classroom_id',$classroom['id'])
                            ->first();

                       $check = TimeTable::query()
                       ->where('lessonDay_id',$lessonDay->id)
                           ->where('teacher_id',$lesson['teacher_id'])
                           ->first(['teacher_id','classClassroom_id']);
                       $subjectTeacher = DB::table('teacher__subjects')
                           ->where('teacher_id',$lesson['teacher_id'])
                           ->first('subject_id');

                       if($check)
                       {
                           $teacherName = Teacher::query()
                               ->where('id',$lesson['teacher_id'])
                               ->first(['f_name','l_name']);
                           $lessonName = Lesson::query()
                               ->where('id',$lesson['id'])
                               ->first('name');
                           $classroomId = DB::table('claass_classrooms')
                               ->where('id',$check->classClassroom_id)
                               ->first('classroom_id');
                           $dayName = Day::query()
                               ->where('id',$day['id'])
                               ->first('name');

                return $this->returnErrorMessage("error in day:".$dayName->name ." "."in lesson:".$lessonName->name." "."this teacher:".$teacherName->f_name." ". $teacherName->l_name." ".
                "is already exist in classroom:".$classroomId->classroom_id." ",400);
                       }
                       else{
                           $timetaple=TimeTable::query()->create([

                               'lessonDay_id' =>$lessonDay->id,
                               'classClassroom_id'=>$classClassroom->id,
                               'teacher_id'=>$lesson['teacher_id'],
                               'subject_id'=>$subjectTeacher->subject_id

                           ]);

                       }
                    }
            }
        }

        return $this->returnSuccessMessage("success");
    }


    public function studentTimetable(Claass $class,Classroom $classroom)
    {
        $classClassroom = ClassClassroom::query()
            ->where('class_id',$class->id)
            ->where('classroom_id',$classroom->id)
            ->first();

        $timetables = TimeTable::query()
            ->where('classClassroom_id',$classClassroom->id)
            ->with('teacher')
            ->with('subject')
            ->with('lesson',function ($query){
                $query->with('lessons','days');
            })
//            ->with('classroom',function ($query){
//                $query->with('classes','classrooms');
//            })
            ->get();


        return $this->returnAllData('studentTimetable',$timetables,'success');
    }

    public function teacherTimetable(Teacher $teacher){

        $timetables = TimeTable::query()
            ->where('teacher_id',$teacher->id)
            ->with('subject')
            ->with('lesson',function ($query){
                $query->with('days','lessons');
            })
            ->with('classroom',function ($query){
                $query->with('classes','classrooms');
            })
            ->get();

        return $this->returnAllData('teacherTimetable',$timetables,'success');

    }

    public function mentorTimetable(Mentor $mentor){
        $class = Mentor::query()
            ->where('id',$mentor->id)
            ->first('class_id');

        $classClassrooms = ClassClassroom::query()
            ->where('class_id',$class->class_id)
            ->select('classroom_id','id')
            ->get();

        foreach ($classClassrooms as $classClassroom){

            $timetables = TimeTable::query()
                ->where('classClassroom_id',$classClassroom->id)
                ->with('teacher')
                ->with('subject')
                ->with('lesson',function ($query){
                    $query->with('lessons','days');
                })
                ->with('classroom',function ($query){
                $query->with('classes','classrooms');
                })
                ->get();
            foreach ($timetables as $timetable){
                $result[] = $timetable;
            }

        }
        return $this->returnAllData('studentTimetable',$result,'success');
    }
}
