<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\ClassClassroom;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamName;
use App\Models\Question;
use App\Models\QuestionExam;
use App\Models\QuizMarks;
use App\Models\Student;
use App\Models\SubjectMark;
use App\Models\TeacherSubject;
use App\Traits\basicFunctionsTrait;
use App\Traits\generalTrait;
use Illuminate\Http\Request;
use App\Models\Claass;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;
use function PHPUnit\Framework\isEmpty;

class ExamController extends Controller
{
    use generalTrait, basicFunctionsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exam::query()->get();
        return $this->returnAllData('exams', $exams, 'all exams');

    }

    public function mark_ladder(Exam $exam)
    {
        $exam_mark = Exam::where('id', $exam->id)->get('mark');
        $exam_name = ExamName::where('id', $exam->exam_name_id)->get('name');
        $a = QuestionExam::where('exam_id', $exam->id)->pluck('question_id');
        foreach ($a as $aa) {

            $question_text[] = Question::query()->where('id', $aa)->with('choices', function ($query) {
                return $query->where('status', true);
            })->get();

            // $choise[]=Choice::query()->where('question_id',$aa)->where('status',true)->pluck('text');

//          return $this->returnData('exam_info', $exam_info, 'exam_info');

        }

//        $data['exam_mark']=$exam_mark;
//        $data['exam_name']=$exam_name;
//        $data['question_text']=$question_text;
        //  $data['choise']=$choise;

//
//        return $this->returnData('exam_info', $data, 'exam_info');


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $name1 = ExamName::query()
            ->where('id',$request->exam_name_id)
            ->where('name','First')
            ->first();
        $name2 = ExamName::query()
            ->where('id',$request->exam_name_id)
            ->where('name','Second')
            ->first();
        $name3 = ExamName::query()
            ->where('id',$request->exam_name_id)
            ->where('name','Mid')
            ->first();
        $name4 = ExamName::query()
            ->where('id',$request->exam_name_id)
            ->where('name','Final')
            ->first();

        $subject_mark = DB::table('subject_mark')
            ->where('subject_id', $request->subject_id)
            ->where('class_id', $request->class_id)
            ->first();

        if (!isset($subject_mark)) {
            return $this->returnErrorMessage('there is not relationship between class & subject', 404);
        }
        $checkDate = $this->checkStartAndEndDate($request->start, $request->end);
        if ($checkDate == false) {
            return $this->returnErrorMessage('exam time must be checked', 400);
        }


        if(isset($name1)||isset($name2)) {
            $mark = (20 / 100) * $subject_mark->mark;
        }
        if(isset($name3))

            $mark=(20/100)*$subject_mark->mark;

        if(isset($name4))

            $mark=(40/100)*$subject_mark->mark;

        $subjectMarkOnlySameClass = DB::table('subject_mark')
            ->where('class_id', $request->class_id)
            ->get();
        $vari = 0;
        foreach ($subjectMarkOnlySameClass as $item) {
            $allExam = Exam::query()->where('subject_mark_id', $item->id)->get();
            foreach ($allExam as $value) {
                $start = Carbon::parse($value->start)->subMinute();
                $end = Carbon::parse($value->end);
                $checkStart = Carbon::parse($request->start)->between($start, $end);
                $checkEnd = Carbon::parse($request->end)->between($start, $end);
                if ($checkStart || $checkEnd) {
                    return $this->returnErrorMessage('already this class has exam in this date', 400);
                }
            }
        }

        foreach ($request->questions as $question) {
            $vari += $question['mark'];
        }
//         if sum of mark questions != exam mark
        if ($vari != $mark) {
            return $this->returnSuccessMessage('Excuse Me!!!');
        }
        $exam = Exam::query()->create([
            'mark' => $mark,
            'exam_name_id' => $request->exam_name_id,
            'subject_mark_id' => $subject_mark->id,
            'season_id' => $request->season_id,
            'start' => $request->start,
            'end' => $request->end,
        ]);
        foreach ($request->questions as $question) {

            DB::table('question_exams')->insert([
                'mark' => $question['mark'],
                'question_id' => $question['question_id'],
                'exam_id' => $exam->id
            ]);
        }
        return $this->returnData('exam', $exam, 'success');

    }




    public function show(Exam $exam)
    {
        return $this->returnData('exam', $exam, 'success');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        $exam->update([
            'mark' => $request->mark,
            'exam_name_id' => $request->exam_name_id,
            'subject_mark_id' => $request->subject_mark_id
        ]);

        return $this->returnData('exam', $exam, 'updated exam successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();
        return $this->returnSuccessMessage('deleted exam successfully');

    }


//    end exam & show mark
    public function studentMark(Request $request,Exam $exam,Student $student)
    {

        $student_mark = 0;

        $examEndTime = Exam::query()->select('end')->where('id', $exam->id)->first();
        $nowTime = Carbon::now()->subMinutes(2)->toDateTimeString();
        if ($nowTime <= $examEndTime->end){
            foreach($request->questions as $question){
                $status = DB::table('choices')
                    ->where('question_id',$question['question_id'])
                    ->where('id',$question['choice_id'])
                    ->select('status')
                    ->first();
                if (!$status == null) {
                    if ($status->status == 1) {
                        $question_mark = DB::table('question_exams')
                            ->where('exam_id', $exam->id)
                            ->where('question_id', $question['question_id'])
                            ->select('mark')
                            ->first();
                        if(!$question_mark->mark){

                            return $this->returnErrorMessage('this question related to another exam', 400);
                        }
                        $student_mark += $question_mark->mark;
                    }
                }
            }
            $exam = DB::table('exam_marks') ->insert([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'mark' => $student_mark,
            ]);

            return $this->returnMark( $student_mark, 'success');

        }else if ($nowTime >= $examEndTime->end){
            $exam = DB::table('exam_marks') ->insert([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'mark' => $student_mark,
            ]);

            return $this->returnMark( $student_mark, 'GAMEOVER');

        }
        return $this->returnError('input error', 400);

    }


//     start exam
    public function GetExamQuestion(Exam $exam){
        $nowOclock = Carbon::now();
        $studentId = auth('student')->id();
        $checkMark = ExamMark::query()->where('exam_id', $exam->id)->where('student_id', $studentId)->first();
        if (isset($checkMark)) {
            return $this->returnErrorMessage('Sorry,  already taken this exam', 403);
        }

        $exam = Exam::query()
            ->where('id',$exam->id)
            ->Where('end','>',  $nowOclock->format('Y-m-d H:i:0'))
            ->where('start','<=', $nowOclock->format('Y-m-d H:i:0'))
            ->first();

        if (! isset($exam)) {
            return $this->returnErrorMessage('exam not found', 404);
        }

        $questions = $exam->load('questions');
        return $this->returnData('exams', $questions, 'GOODLUCK');

    }

//    schedule exam
    public function GetClassExam(Claass $claass){

//        $classId = $student->classClassroom->class_id;
        $classExams = DB::table('subject_mark')
            ->where('class_id', $claass->id)
            ->get();

        foreach($classExams as $classExam)
        {
            $allClassExam = Exam::query()
                ->where('active', 1)
                ->where('end', '>=', Carbon::now())
                ->where('subject_mark_id',$classExam->id)
                ->with('subjectMark',function ($query){
                    $query->with('subject');

                })
                ->get();
            $subject = DB::table('subjects')
                ->where('id',$classExam->subject_id)
                ->select('name')
                ->first();
            ////مزاكرتين خلال الفصل الواحد لنفس المادة بنفس الصف في المرحلة الابتدائية
            foreach ($allClassExam as $item) {
//                $exams[] = $subject;
                $exams[] = $item;
            }
        }
//        if (isEmpty($exams)){
//            return true;
//        }
        try {
            return $this->returnAllData('exams',$exams, 'all classExam');

        }catch (\Exception $exception) {
            return $this->returnSuccessMessage('not found');
        }


    }

}
