<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Claass;
use App\Models\ClassClassroom;
use App\Models\Question;
use App\Models\Subject;
use App\Models\SubjectMark;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use App\Traits\generalTrait;
use Illuminate\Support\Facades\DB;
use App\Traits\basicFunctionsTrait;
class QuestionController extends Controller
{
    use generalTrait, basicFunctionsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mark = 0;
        $subjectId = $request->subject_id;
        $classId  = $request->class_id;
        $teacherId = $request->teacher_id;

        if(isset($request->type)) {
            $subject_mark = SubjectMark::query()->where('subject_id', $subjectId)->where('class_id', $classId)->first();
            if ($request->type == 1 || $request->type == 2 ) {
                $mark = (20 / 100) * $subject_mark->mark;
            }
            if ($request->type == 3 || $request->type == 5) {
                $mark=(20/100)*$subject_mark->mark;
            }
            if ($request->type == 4) {
                $mark=(40/100)*$subject_mark->mark;
            }
//            if ($request->type == 5) {
//                $mark=(20/100)*$subject_mark->mark;
//            }


        }

        $teacherSubjectClass = $this->checkOwnerQuestion($classId, $subjectId, $teacherId);
        if ($teacherSubjectClass == null) {
            return $this->returnErrorMessage('input error', 400);
        }
//        $examMark = SubjectMark::query()->where('subject_id', $subjectId)->where('class_id', $classId)->first('mark');

        $questions = Question::query()
            ->where('teacher_subjects_id', $teacherSubjectClass->id)
            ->with('choices')
            ->get();
        $data['max mark'] = $mark;
        $data['questions'] = $questions;
        return $this->returnData('questions', $data, 'all questions');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subjectId = $request->subject_id;
        $classId  = $request->class_id;
        $teacherId = $request->teacher_id;
//
//        $classClassroom = DB::table('claass_classrooms')
//            ->where('class_id',$request->class_id)
//            ->select('id')
//            ->first();
//
//        if (!isset($classClassroom)){
//            return $this->returnErrorMessage('input error', 400);
//        }
//        $teacherSubjectClass = DB::table('teacher__subjects')
//            ->where('teacher_id',$request->teacher_id)
//            ->where('subject_id',$request->subject_id)
//            ->where('class_classroom_id',$classClassroom->id)
//            ->select('id')
//            ->first();
//
//        if (!isset($teacherSubjectClass)){
//            return $this->returnErrorMessage('input error', 400);
//        }
        $teacherSubjectClass = $this->checkOwnerQuestion($classId, $subjectId, $teacherId);
        if ($teacherSubjectClass == null) {
            return $this->returnErrorMessage('input error', 400);
        }
        foreach ($request->question as $question) {
            $newQuestion = Question::query()->create([
                'text' => $question['text'],
                'teacher_subjects_id' => $teacherSubjectClass->id
            ]);
            foreach ($question['chioces'] as $chioce) {
                $newQuestion->choices()->create([
                    'text' => $chioce['text'],
                    'status' => $chioce['status']
                ]);
            }
        }
        return  $this->returnSuccessMessage('success');
    }


    public function update(Request $request,Question $question)
    {
//        $teacherId = $question->teacherSubjects->teacher_id;
//        if ($teacherId != $request->teacher_id){
//            return $this->returnErrorMessage('UnAuthorized', 403);
//        }

        $question->update([
            'text'=>$request->text,
        ]);

        foreach ($request->choices as $choice) {
            $chioceTrue =$question->choices()->where('status', 1)->first();
            if ($choice['status'] == 1 && $choice['id'] != $chioceTrue->id){
                $chioceTrue->update([
                    'status' => 0
                ]);
            }
            $question->choices()->where('id', $choice['id'])->update([
                'text' => $choice['text'],
                'status' => $choice['status']
            ]);
        }
        return  $this->returnData('questions', $question->load('choices'), 'updated question successfully');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return $this->returnSuccessMessage('deleted question successfully');
    }
}
