<?php

namespace App\Http\Controllers\Resultant;

use App\Http\Controllers\Controller;
use App\Models\ClassClassroom;
use App\Models\ExamMark;
use App\Models\Quiz;
use App\Models\QuizMarks;
use App\Models\Season;
use App\Models\Student;
use App\Models\Subject;
use App\Traits\basicFunctionsTrait;
use App\Traits\generalTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class ResultantController extends Controller
{
    use generalTrait, basicFunctionsTrait;

    public function resultantStudent(Student $student , Season $season) {

        $classClassroom = $student->classClassroom;
        $class = $classClassroom->classes;
        $classSubjects = $classClassroom->classes->subjects;

//        $classroom = $student->classroom;
//        $classClassroom = ClassClassroom::query()
//            ->where('class_id',$class->id)
//            ->where('classroom_id',$classroom->id)
//            ->first();


        $numberOfQuizes = 0;
        $sumOfQuizeMarks = 0;
        $sumOfOralMarks = 0;
        $numberOfOral = 0;
        $sumOfSExamMarks = 0;
        $sumOfLExamMarks = 0;
        $examResult = 0;
        $LExamResult = 0;
        $i = 0;
        $array[] = 0;


        foreach ($classSubjects as $classSubjectt) {

            $subjects = DB::table('teacher__subjects')
                ->where('subject_id', $classSubjectt->id)
                ->where('class_classroom_id',$classClassroom->id)
                ->get();

            if(!$subjects){
                return $this->returnErrorMessage('this class dont have this subject', 400);
            }

            foreach ($subjects as $subjectt) {

                $quizzes = DB::table('quizzes')
                    ->where('teacher_subject_id', $subjectt->id)
                    ->where('season_id',$season->id)
                    ->get();

                foreach ($quizzes as $quiz) {
                    if ($quiz->quiz_name_id == 2) {

                    $studentQuize = DB::table('quiz_marks')
                        ->where('student_id', $student->id)
                        ->where('quiz_id', $quiz->id)
                        ->first();
                     if (isset($studentQuize)) {
                         $numberOfQuizes++;
                         $sumOfQuizeMarks += $studentQuize->mark;
                     }

                    }


                        if ($quiz->quiz_name_id == 1) {
                            $studentOralQuiz = DB::table('quiz_marks')
                                ->where('student_id', $student->id)
                                ->where('quiz_id', $quiz->id)
                                ->get();

                            if (isset($studentOralQuiz)) {
                                foreach ($studentOralQuiz as $item) {
                                    $numberOfOral++;
                                    $sumOfOralMarks += $item->mark;
                                }
                            }

                        }


                }
                if($numberOfQuizes==0)
                {
                    $quizeResult = 0;

                }else{

                    $quizeResult = $sumOfQuizeMarks / $numberOfQuizes;
                }
                $numberOfQuizes = 0;
                $sumOfQuizeMarks = 0;
                if($numberOfOral == 0)
                {
                    $oralResult = 0;
                }else{

                    $oralResult = $sumOfOralMarks / $numberOfOral ;
                }

                $numberOfOral = 0;
                $sumOfOralMarks = 0;

            }

            $classSubjectts = DB::table('subject_mark')
                ->where('subject_id', $classSubjectt->id)
                ->where('class_id', $class->id)
                ->first();

        $exams = DB::table('exams')
            ->where('subject_mark_id',$classSubjectts->id)
            ->where('season_id',$season->id)
            ->get();

        foreach ($exams as $exam) {

            $studentExams = DB::table('exam_marks')
                ->where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->first();
            if (isset($studentExams)) {

                if ($exam->exam_name_id == 1 || $exam->exam_name_id == 2) {
                    $sumOfSExamMarks += $studentExams->mark;
                    $examResult = $sumOfSExamMarks / 2;
                }

                if ($exam->exam_name_id == 3) {
                    $examResult = $studentExams->mark;
                }
                if ($exam->exam_name_id == 4) {
                    $LExamResult = $studentExams->mark;

                }
            }
        }
            $totalSeasonMark = $LExamResult + ($examResult + $quizeResult + $oralResult) / 2;
            $array[$i] = [
                'subjectName' => $classSubjectt->name,
                'subjectMark' => $classSubjectts->mark,
                'quize' => $examResult,
                'exam' => $LExamResult,
                'test' => $quizeResult ,
                'oralTest' => $oralResult ,
                'totalMark' => $totalSeasonMark
            ];
            $i++;

            $quizeResult = 0;
            $oralResult = 0;
            $examResult = 0;
            $LExamResult = 0;

        }
        return $this->returnAllData('resultant', $array , 'success');
    }
}
