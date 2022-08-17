<?php

namespace App\Http\Controllers;

use App\Models\ClassClassroom;
use App\Models\OnlineClass;
use App\Models\TeacherSubject;
use App\Traits\basicFunctionsTrait;
use App\Traits\generalTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
class OnlineClassController extends Controller
{
    use basicFunctionsTrait,generalTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $class = $request->class_id;
        $classroom = $request->classroom_id;
        $teacher = $request->teacher_id;
        $subject = $request->subject_id;

        $checkTeacherSubject = $this->checkTeacherSubject($teacher,$subject,$class,$classroom);

        if (! isset($checkTeacherSubject)) {
            return $this->returnErrorMessage('input error', 400);
        }

        $classClassroom = ClassClassroom::query()
            ->where('class_id',$class)
            ->where('classroom_id',$classroom)
            ->first('id');

        $teacherSubject = TeacherSubject::query()
            ->where('teacher_id',$teacher)
            ->where('subject_id',$subject)
            ->where('class_classroom_id',$classClassroom->id)
            ->first('id');

        $onlineClass = OnlineClass::query()->create([
           'link' => $request->link,
           'date' => $request->date,
           'teacher_subject_id' => $teacherSubject->id
        ]);

        return $this->returnData('data', $onlineClass, 'success');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function teacherOnlineClass(Teacher $teacher)
    {
        $teacherSubjects = TeacherSubject::query()
            ->where('teacher_id',$teacher->id)
            ->get('id');

        foreach ($teacherSubjects as $teacherSubject){
            $onlineClasses = OnlineClass::query()
                ->where('teacher_subject_id',$teacherSubject->id)
                ->with('teacherSubject',function ($query){
                    $query->with('subjects')
                        ->with('classClassrooms',function ($query){
                           $query->with('classes')
                           ->with('classrooms');
                        });
                })->get();
            foreach ($onlineClasses as $onlineClass){
                $result[] = $onlineClass;
            }

        }
        try {
            return $this->returnAllData('data', $result, 'teacherOnlineClass');

        }catch (\Exception $exception) {
            return $this->returnAllData('data', [], 'teacherOnlineClass');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function studentOnlineClass(Request $request , $class,$classroom)
    {
            $classClassroom = ClassClassroom::query()
                ->where('class_id',$class)
                ->where('classroom_id',$classroom)
                ->first();
            if (!isset($classClassroom)) {
                return $this->returnErrorMessage('classroom not found',404);
            }
        $teacherSubjects = TeacherSubject::query()
            ->where('class_classroom_id',$classClassroom->id)
            ->get();

         foreach ($teacherSubjects as $teacherSubject){
             $onlineClasses = OnlineClass::query()
                 ->where('teacher_subject_id',$teacherSubject->id)
                 ->with('teacherSubject',function ($query){
                     $query->with('subjects')
                         ->with('teachers');
//                         ->with('classClassrooms',function ($query){
//                             $query->with('classes')
//                                 ->with('classrooms');
//                         });
                 })->get();
             foreach ($onlineClasses as $onlineClass){
                 $result[] = $onlineClass;
             }
         }
        try {
            return $this->returnAllData('data', $result, 'teacherOnlineClass');
        }catch (\Exception $exception) {
             return $this->returnSuccessMessage('not found');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
