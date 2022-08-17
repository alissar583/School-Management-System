<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\ClassClassroom;
use App\Models\Subject;
use App\Models\SubjectMark;
use App\Traits\basicFunctionsTrait;
use Illuminate\Http\Request;
use App\Models\Claass;
use App\Models\Day;
use App\Models\Teacher;
use App\Traits\generalTrait;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use phpDocumentor\Reflection\Types\True_;

class ManagementController extends Controller
{
    use generalTrait, basicFunctionsTrait;

     public function addClassroomToClass(Request $request, Claass $claass) {
         $claass -> classroom()->syncWithoutDetaching($request -> classroom_Id);
         return $this->returnSuccessMessage('added classroom to class successfully');
     }

    public function addLessonsToDays(Request $request, Day $day) {
        $day -> lessons()->syncWithoutDetaching($request -> lesson_id);
        return $this->returnSuccessMessage('added lessons to day successfully');
    }

//    public function addClassroomToTeacher(Request $request, Teacher $teacher) {
//        $teacher -> classClassroom()->syncWithoutDetaching($request -> claass_classroom_id);
//        return $this->returnSuccessMessage('added classroom to teacher successfully');
//    }

    public function addSubjectToTeacher(Request $request, Teacher $teacher) {
        $checkClassClassroom = $this->checkClassClassroom($request->class_id, $request->classroom_id);
        if (!$checkClassClassroom == null) {
            $check =  DB::table('teacher__subjects')
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $teacher->id)
                ->where('class_classroom_id', $checkClassClassroom->id)
                ->first();
            if (isset($check)) {
                return $this->returnSuccessMessage('already exists');
            }else {
                if (Subject::query()->find($request->subject_id)) {
                    DB::table('teacher__subjects')->insert([
                        'teacher_id' => $teacher->id,
                        'subject_id' => $request->subject_id,
                        'class_classroom_id' => $checkClassClassroom->id,
                    ]);
                    return $this->returnSuccessMessage('added subject to teacher successfully');
                }else {
                    return $this->returnErrorMessage('subject not found', 404);
                }
            }
            return $this->returnErrorMessage('input error', 400);
        }
        return $this->returnErrorMessage('class or classroom not found', 404);

    }



    public function addSubjectToClass(Request $request, Claass $class) {
        foreach ($request->subjects as $item){
            if (Subject::query()->find($item['subject_id'])){
                $check = SubjectMark::query()->where('class_id', $class->id)->where('subject_id', $item['subject_id'])->first();
                if (isset($check)) {
                    return $this->returnSuccessMessage('the subject '. $check->subject->name . ' already exists in this class');
                }
                DB::table('subject_mark')->insert([
                    'subject_id' => $item['subject_id'],
                    'mark' => $item['mark'],
                    'class_id' => $class->id
                ]);
            }

        }
        return $this->returnSuccessMessage('added subject to class successfully');
    }

//      delete
//    public function customizeTeachForClassroom(Request $request) {
//       $teacherId = $request->teacher_id;
//       $subjectId = $request->subject_id;
//       $claassId = $request->class_id;
//       $classroomId = $request->classroom_id;
//
//       $classClassroom = $this->checkClassClassroom($claassId, $classroomId);
//        if ($classClassroom == null) {
//            return $this->returnErrorMessage('class or classroom not found', 404);
//        }
//
//        $teachSubject = $this->checkTeacherSubject($teacherId, $subjectId);
//        if ($teachSubject == null) {
//            return $this->returnErrorMessage('teacher or subject not found', 404);
//        }
//
//        $classClassroomId = $classClassroom->id;
//        $teachSubjectId = $teachSubject->id;
//
//       $C_CR_T_S_ID = DB::table('claass_classroom_teacher_subject')
//           ->select('id')
//           ->where('t_s_id', $teachSubjectId)
//           ->where('c_cr_id', $classClassroomId)
//           ->first();
//
//       if (!isset($C_CR_T_S_ID)) {
//           DB::table('claass_classroom_teacher_subject')->insert([
//               't_s_id' => $teachSubjectId,
//               'c_cr_id' => $classClassroomId
//           ]);
//
//           return $this->returnSuccessMessage('success');
//       }
//        return $this->returnSuccessMessage('already exists');
//
//    }


    public function allSubjectsWithClasses() {
         $subjects = Subject::query()->with('classes', function ($query) {
             $query->with('classroom');
         })->get();
         return $this->returnAllData('data', $subjects, 'success');
    }
//    get classrooms and teacher for class
    public function getClassroomAndTeacher(Claass $class) {
         $data = ClassClassroom::query()->where('class_id', $class->id)->with('classrooms','teacher')->get();
         return $this->returnAllData('data', $data, 'success');
     }
}
