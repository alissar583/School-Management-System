<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Claass;
use App\Models\ClassClassroom;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\QuestionQuiz;
use App\Models\Quiz;
use App\Models\QuizMarks;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Traits\basicFunctionsTrait;
use App\Traits\generalTrait;
use Carbon\Carbon;
use Dotenv\Repository\Adapter\ReplacingWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\True_;
use App\Models\SubjectMark;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class QuizController extends Controller
{
    use generalTrait, basicFunctionsTrait;



    public function index()
    {
        $quizzes = Quiz::query()->get();
        return $this->returnAllData('quiz', $quizzes, 'quiz');
    }

    public function store(Request $request)
    {
        $teacherId = $request->teacher_id;
        $subjectId = $request->subject_id;
        $claassId = $request->class_id;
        $classroomId = $request->classroom_id;


        $subject_mark = $this->checkHasRelationBetweenClassAndSubject($claassId, $subjectId);

        if (!isset($subject_mark)) {
            return $this->returnErrorMessage('there is not relationship between subject and class', 404);
        }
        $checkDate = $this->checkStartAndEndDate($request->start, $request->end);
        if ($checkDate == false) {
            return $this->returnErrorMessage('must be check quiz date', 400);
        }

        $mark=(20/100)*$subject_mark->mark;
        $vari = 0;


        $check = $this->checkTeacherSubject($teacherId, $subjectId, $claassId, $classroomId);
        if (isset($check)) {
            foreach ($request->questions as $question) {
                $vari += $question['mark'];
            }
            if ($vari != $mark) {
//                if sum of mark questions != quiz mark
                return $this->returnSuccessMessage('sum of mark questions != quiz mark');
            }
            $quiz = Quiz::query()->create([
                'mark' => $mark,
                'quiz_name_id' => 2,
                'teacher_subject_id' => $check->id,
                'season_id' => $request->season_id,
                'start' => $request->start,
                'end' => $request->end,
            ]);
            foreach ($request->questions as $question) {
                DB::table('question_quizzes')->insert([
                    'mark' => $question['mark'],
                    'question_id' => $question['question_id'],
                    'quiz_id' => $quiz->id
                ]);
            }
            return $this->returnData('quiz', $quiz, 'success');
        }
        return $this->returnErrorMessage('input error', 400);
    }

//    اختبار شفهي
    public function addOralQuiz(Request $request) {
        $studentId = $request->student_id;
        $teacherId = $request->teacher_id;
        $subjectId = $request->subject_id;
        $claassId = $request->class_id;
        $classroomId = $request->classroom_id;
        Student::query()->findOrFail($studentId);

        $subject_mark = $this->checkHasRelationBetweenClassAndSubject($claassId, $subjectId);

        if (!isset($subject_mark)) {
            return $this->returnErrorMessage('there is not relationship between subject and class', 404);
        }

        $mark=(80/100)*$subject_mark->mark;
        $check = $this->checkTeacherSubject($teacherId, $subjectId, $claassId, $classroomId);
        if (isset($check)) {
            $quiz = Quiz::query()
                ->where('teacher_subject_id', $check->id)
                ->where('season_id', $request->season_id)
                ->where('quiz_name_id', 1)
                ->first();
            if (!isset($quiz)) {
                $quiz = Quiz::query()->create([
                    'mark' => $mark,
                    'quiz_name_id' => 1,
                    'teacher_subject_id' => $check->id,
                    'season_id' => $request->season_id,
                    'start' => Carbon::now()->subMinutes(2)->format('Y-m-d H:i:0'),
                    'end' => Carbon::now()->format('Y-m-d H:i:0'),
                ]);
            }
            if ($request->mark > $mark) {
                return $this->returnErrorMessage('mark must be less than '. $mark, 200);
            }
            DB::table('quiz_marks')->insert([
                'quiz_id' => $quiz->id,
                'student_id' => $studentId,
                'mark' => $request->mark
            ]);
            return $this->returnSuccessMessage('success');
        }
        return $this->returnErrorMessage('input error', 400);
    }

    public function getStudentsForOralQuiz(Request $request) {
        $teacherId = $request->teacher_id;
        $subjectId = $request->subject_id;
        $claassId = $request->class_id;
        $classroomId = $request->classroom_id;

        $classClassroomId = $this->checkClassClassroom($request->class_id, $request->classroom_id);

        $subject_mark = $this->checkHasRelationBetweenClassAndSubject($claassId, $subjectId);

        if (!isset($subject_mark)) {
            return $this->returnErrorMessage('there is not relationship between subject and class', 404);
        }
        $check = $this->checkTeacherSubject($teacherId, $subjectId, $claassId, $classroomId);
        if (isset($check, $classClassroomId)) {
            $students = Student::query()->where('class_classroom_id', $classClassroomId->id)->get();
            return $this->returnAllData('student', $students, 'success');
        }
        return $this->returnErrorMessage('input error', 400);
    }

    public function show(Quiz $quiz)
    {
        return $this->returnData('quiz', $quiz, 'success');
    }

    public function update(Request $request, Quiz $quiz)
    {
        $teacherId = $request->teacher_id;
        $subjectId = $request->subject_id;
        $claassId = $request->class_id;
        $classroomId = $request->classroom_id;

        $check = $this->checkTeacherSubject($teacherId, $subjectId, $claassId, $classroomId);
        if (isset($check)) {
            $quiz->update([
                'teacher_subject_id' => $check->id,
            ]);
            return $this->returnData('quiz', $quiz, 'success');
        }
        return $this->returnErrorMessage('input error', 400);

    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return $this->returnSuccessMessage('success');
    }

    public function markLadder(Quiz $quiz) {

        $quizInfo = $this->quizInfo($quiz);
        $questions = $quiz::query()->with('questions', function ($query) {
            $query->with('choices', function ($query) {
                $query->where('status', true);
            });
        })->get();

        $data['quizInfo'] = $quizInfo;
        $data['questions'] = $questions;
        return $this->returnData('data', $data, 'success');

    }


    public function getQuiz(Quiz $quiz) {
        $nowTime = Carbon::now();
        $studentId = auth('student')->id();
        $checkMark = QuizMarks::query()->where('quiz_id', $quiz->id)->where('student_id', $studentId)->first();
        if (isset($checkMark)) {
            return $this->returnErrorMessage('Sorry,  already taken this quiz', 403);
        }

            $quiz = Quiz::query()
                ->Where('end','>',  $nowTime->format('Y-m-d H:i:0'))
                ->where('start','<=', $nowTime->addMinute()->format('Y-m-d H:i:0'))
                ->where('id', $quiz->id)
                ->first();
//        }
        if (! isset($quiz)) {
            return $this->returnErrorMessage('quiz not found', 404);
        }

        $questions = $quiz->load('questions');
        return $this->returnData('data', $questions, 'success');
    }


    public function studentQuizMark(Quiz $quiz, Student $student, Request $request)
    {

        $studentMark = 0;
        $nowTime = Carbon::now()->subMinutes(2)->toDateTimeString();

        if ($nowTime <= $quiz->end) {
            foreach ($request->questions as $question) {
                $status = DB::table('choices')
                    ->where('question_id', $question['question_id'])
                    ->where('id', $question['choice_id'])
                    ->select('status')
                    ->first();
                if (!$status == null) {
                    if ($status->status == 1) {
                        $question_mark = DB::table('question_quizzes')
                            ->where('quiz_id', $quiz->id)
                            ->where('question_id', $question['question_id'])
                            ->select('mark')
                            ->first();


                        $studentMark += $question_mark->mark;
                    }
                }
            }
            $quiz = DB::table('quiz_marks') ->insert([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'mark' => $studentMark,
            ]);

            return $this->returnMark( $studentMark, 'success');

        }else if ($nowTime >= $quiz->end){
            $exam = DB::table('quiz_marks') ->insert([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'mark' => $studentMark,
            ]);

            return $this->returnMark( $studentMark, 'GAMEOVER');
        }
        return $this->returnErrorMessage('input error', 400);
    }




    public function quizScheduleForClassroom(Claass $claass, Classroom $classroom)
    {
        $q = null;
        $checkClassClassroom = $this->checkClassClassroom($claass->id, $classroom->id);
        if (!isset($checkClassClassroom)) {
            return $this->returnErrorMessage('input error', 400);
        }

        $teacherSubjects = TeacherSubject::query()
            ->where('class_classroom_id', $checkClassClassroom->id)
            ->get();

        foreach ($teacherSubjects as $teacherSubject) {
            $quizzes = Quiz::query()
                ->where('teacher_subject_id', $teacherSubject->id)
                ->where('end', '>=', Carbon::now())
                ->get();
            if ($quizzes->isNotEmpty()) {
                foreach ($quizzes as $quiz) {
                    $q[] = $quiz->load('teacherAndSubject');
                }
            }
        }
        if (!isset($q)) {
            return $this->returnSuccessMessage('not found');
        }
        return $this->returnAllData('quizzes', $q, 'success');

    }
}
