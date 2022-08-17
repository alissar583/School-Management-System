<?php
namespace App\Traits;

use App\Models\Address;
use App\Models\Blood;
use App\Models\ClassClassroom;
use App\Models\SubjectMark;
use Illuminate\Support\Facades\DB;


trait basicFunctionsTrait{
    use generalTrait;
    public function addAddress($request) {

         $address  = Address::query()
            ->where('city', $request->city)
            ->where('street', $request->street)
            ->where('town', $request->town)
            ->first();
        if (!isset($address)) {
            $address = Address::query()->create([
                'city' => $request->city,
                'street' => $request->street,
                'town' => $request->town,
            ]);
            return $address;
        }
        return $address;


    }
    public function getBloods() {
        $bloods = Blood::query()->get();
        return $bloods;
    }

    public function checkClassClassroom($claassId, $classroomId) {
        $classClassroom = ClassClassroom::query()
            ->select('id')
            ->where('class_id', $claassId)
            ->where('classroom_id', $classroomId)
            ->first();
        return $classClassroom;
    }

    public function checkStartAndEndDate($start, $end) {
        if ($end <= $start) {
            return false;
        }
        return true;
    }


    public function checkTeacherSubject($teacherId, $subjectId, $classId, $classroomId) {
        $test = $this->checkClassClassroom($classId, $classroomId);
        if (isset($test)) {
            $teachSubject = DB::table('teacher__subjects')
                ->select('id')
                ->where('subject_id', $subjectId)
                ->where('teacher_id', $teacherId)
                ->where('class_classroom_id', $test->id)
                ->first();
            return $teachSubject;
        }
    }

    public function quizInfo($quiz) {
        $quizInfo = DB::table('quizzes')
            ->select(['quiz_name_id', 'teacher_subject_id'])
            ->where('id', $quiz->id)->first();

        $teacSub = DB::table('teacher__subjects')
            ->where('id', $quizInfo->teacher_subject_id)->first();

        $classClassroom = ClassClassroom::query()->where('id', $teacSub->class_classroom_id)->first();
        $class = DB::table('claasses')
            ->select(['id', 'name', 'grade_id'])
            ->where('id', $classClassroom->class_id)->first();

        $classroom = DB::table('classrooms')->select('name')
            ->where('id', $classClassroom->classroom_id)->first();

//        $teacherSubjcet = DB::table('teacher__subjects')
//            ->where('id', $C_Cr_T_S_id->t_s_id)->first();

        $teacher = DB::table('teachers')
            ->select(['id', 'f_name', 'l_name', 'picture'])
            ->where('id', $teacSub->teacher_id)->first();

        $subject = DB::table('subjects')
            ->select(['id', 'name'])
            ->where('id', $teacSub->subject_id)->first();

        $data['quiz'] = $quiz;
        $data['subject'] = $subject;
        $data['class'] = $class;
        $data['classroom'] = $classroom;
        $data['teacher'] = $teacher;

        return $data;
    }

    public function checkOwnerQuestion($classId, $subjectId, $teacherId) {

        $classClassroom = DB::table('claass_classrooms')
            ->where('class_id',$classId)
            ->select('id')
            ->first();

        if ($classClassroom == null){
            return $classClassroom;
        }
        $teacherSubjectClass = DB::table('teacher__subjects')
            ->where('teacher_id',$teacherId)
            ->where('subject_id',$subjectId)
            ->where('class_classroom_id',$classClassroom->id)
            ->select('id')
            ->first();

            return $teacherSubjectClass;
    }
    public function checkHasRelationBetweenClassAndSubject($classId, $SubjectId) {
        $subject_mark = SubjectMark::query()
            ->where('subject_id', $SubjectId)
            ->where('class_id', $classId)
            ->first();
        return $subject_mark;
    }
}
