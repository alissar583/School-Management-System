<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AttendanceController;

use function PHPSTORM_META\map;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/admin', [\App\Http\Controllers\Admin\AuthAdminController::class, 'login']);
Route::prefix('admin')->group(function () {
    Route::get('all-exam', [\App\Http\Controllers\Admin\CheckExam::class, 'getAllExam']);
    Route::post('edit-exam-date/{exam}', [\App\Http\Controllers\Admin\CheckExam::class, 'editExamDate']);
    Route::post('accept-exam/{exam}', [\App\Http\Controllers\Admin\CheckExam::class, 'acceptExam']);
    Route::post('accept-syllabi/{syllabi}', [\App\Http\Controllers\Admin\CheckSyllabi::class, 'acceptSyllabi']);
    Route::get('all-syllabi', [\App\Http\Controllers\Admin\CheckSyllabi::class, 'getAllSyllabi']);
    Route::put('edit-settings', [\App\Http\Controllers\SettingController::class, 'update']);
    Route::get('show-settings', [\App\Http\Controllers\SettingController::class, 'show']);
});
Route::prefix('general')->group(function () {
    Route::post('/login', [\App\Http\Controllers\General\LoginController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\General\LoginController::class, 'logout'])->middleware('auth:student,admin,parent,teacher,mentor');
    Route::get('/allSeed', [\App\Http\Controllers\General\GetAllSeedController::class, 'getAllSeed']);
});

Route::prefix('AcademicYear')->group(function () {
//    Route::post('all', [App\Http\Controllers\Academic_year\AcademicYearController::class, 'index']);
    Route::post('add', [App\Http\Controllers\Academic_year\AcademicYearController::class, 'store']);
//    Route::put('update/{yearId}', [App\Http\Controllers\Academic_year\AcademicYearController::class, 'update']);
//    Route::delete('delete/{yearId}', [App\Http\Controllers\Academic_year\AcademicYearController::class, 'destroy']);
});

Route::prefix('student')->group(function () {
    Route::post('all', [\App\Http\Controllers\Student\AddStudentController::class, 'index']);
    Route::post('add', [\App\Http\Controllers\Student\AddStudentController::class, 'store']);
    Route::put('edit/{student}', [\App\Http\Controllers\Student\AddStudentController::class, 'update']);
    Route::get('show/{student}', [\App\Http\Controllers\Student\AddStudentController::class, 'show']);
    Route::delete('delete/{student}', [\App\Http\Controllers\Student\AddStudentController::class, 'destroy']);
});

Route::prefix('parent')->group(function () {
    Route::put('edit/{parent}', [\App\Http\Controllers\Parent\ParentController::class, 'update']);
//    Route::get('show/{parent}', [\App\Http\Controllers\Parent\ParentController::class, 'show']);
    Route::get('child/{parent}', [\App\Http\Controllers\Parent\ParentController::class, 'getParentWithChild']);
//    Route::delete('delete/{parent}', [\App\Http\Controllers\Parent\ParentController::class, 'destroy']);
});

Route::prefix('mentor')->group(function () {
    Route::get('all', [\App\Http\Controllers\Mentor\MentorController::class, 'index']);
    Route::post('add', [\App\Http\Controllers\Mentor\MentorController::class, 'store']);
    Route::put('edit/{mentor}', [\App\Http\Controllers\Mentor\MentorController::class, 'update']);
    Route::get('show/{mentor}', [\App\Http\Controllers\Mentor\MentorController::class, 'show']);
    Route::delete('delete/{mentor}', [\App\Http\Controllers\Mentor\MentorController::class, 'destroy']);
    Route::get('get-students/{mentor}', [\App\Http\Controllers\Mentor\MentorController::class, 'getStudents']);
    Route::get('get-classrooms/{mentor}', [\App\Http\Controllers\Mentor\MentorController::class, 'getClassrooms']);
});

Route::prefix('quiz')->group(function () {
    Route::get('all', [\App\Http\Controllers\Quiz\QuizController::class, 'index']);
    Route::get('mark-ladder/{quiz}', [\App\Http\Controllers\Quiz\QuizController::class, 'markLadder']);
    Route::post('add', [\App\Http\Controllers\Quiz\QuizController::class, 'store']);
    Route::post('add-oral-quiz', [\App\Http\Controllers\Quiz\QuizController::class, 'addOralQuiz']);
    Route::post('students-oral-quiz', [\App\Http\Controllers\Quiz\QuizController::class, 'getStudentsForOralQuiz']);
    Route::get('getQuiz/{quiz}', [\App\Http\Controllers\Quiz\QuizController::class, 'getQuiz'])->middleware('auth:student');
    Route::post('mark/{quiz}/{student}', [\App\Http\Controllers\Quiz\QuizController::class, 'studentQuizMark']);
    Route::put('edit/{quiz}', [\App\Http\Controllers\Quiz\QuizController::class, 'update']);
    Route::get('show/{quiz}', [\App\Http\Controllers\Quiz\QuizController::class, 'show']);
    Route::get('schedule/{claass}/{classroom}', [App\Http\Controllers\Quiz\QuizController::class, 'quizScheduleForClassroom']);
    Route::delete('delete/{quiz}', [\App\Http\Controllers\Quiz\QuizController::class, 'destroy']);
});

Route::prefix('exam')->group(function () {
    Route::get('all', [\App\Http\Controllers\Exam\ExamController::class, 'index']);
    Route::post('add', [\App\Http\Controllers\Exam\ExamController::class, 'store']);
    Route::put('edit/{exam}', [\App\Http\Controllers\Exam\ExamController::class, 'update']);
    Route::get('show/{exam}', [\App\Http\Controllers\Exam\ExamController::class, 'show']);
    Route::get('getExam/{exam}', [\App\Http\Controllers\Exam\ExamController::class, 'GetExamQuestion'])->middleware('auth:student');
    Route::get('classExam/{claass}', [\App\Http\Controllers\Exam\ExamController::class, 'GetClassExam']);
    Route::delete('delete/{exam}', [\App\Http\Controllers\Exam\ExamController::class, 'destroy']);
    Route::post('mark/{exam}/{student}', [\App\Http\Controllers\Exam\ExamController::class, 'studentMark']);

});

Route::prefix('question')->group(function () {
    Route::post('all', [\App\Http\Controllers\Exam\QuestionController::class, 'index']);
    Route::post('add', [\App\Http\Controllers\Exam\QuestionController::class, 'store']);
    Route::put('edit/{question}', [\App\Http\Controllers\Exam\QuestionController::class, 'update']);
    Route::delete('delete/{question}', [\App\Http\Controllers\Exam\QuestionController::class, 'destroy']);
});

Route::prefix('teacher')->group(function () {
    Route::get('all', [\App\Http\Controllers\Teacher\TeacherController::class, 'index']);
    Route::post('add', [\App\Http\Controllers\Teacher\TeacherController::class, 'store']);
    Route::put('edit/{teacher}', [\App\Http\Controllers\Teacher\TeacherController::class, 'update']);
    Route::get('show/{teacher}', [\App\Http\Controllers\Teacher\TeacherController::class, 'show']);
    Route::delete('delete/{teacher}', [\App\Http\Controllers\Teacher\TeacherController::class, 'destroy']);
    Route::get('teacherWithSubjects/{teacher}', [\App\Http\Controllers\Teacher\TeacherController::class, 'getTeacherWithClassroom']);
});

Route::prefix('subject')->group(function () {
    Route::get('all', [\App\Http\Controllers\Subject\SubjectController::class, 'index']);
    Route::post('add', [\App\Http\Controllers\Subject\SubjectController::class, 'store']);
    Route::put('edit/{subject}', [\App\Http\Controllers\Subject\SubjectController::class, 'update']);
    Route::delete('delete/{subject}', [\App\Http\Controllers\Subject\SubjectController::class, 'destroy']);
});

Route::prefix('classroom')->group(function () {
    Route::get('all', [\App\Http\Controllers\Classroom\ClassroomController::class, 'index']);
    Route::post('add', [\App\Http\Controllers\Classroom\ClassroomController::class, 'store']);
    Route::put('edit/{classroom}', [\App\Http\Controllers\Classroom\ClassroomController::class, 'update']);
    Route::post('delete', [\App\Http\Controllers\Classroom\ClassroomController::class, 'destroy']);
});

Route::prefix('syllabi')->group(function () {
    Route::get('all/{class}', [\App\Http\Controllers\syllabi\syllabiController::class, 'index']);
    Route::post('add', [\App\Http\Controllers\syllabi\syllabiController::class, 'store']);
    Route::put('edit/{syllabi}', [\App\Http\Controllers\syllabi\syllabiController::class, 'update']);
    Route::delete('delete/{syllabi}', [\App\Http\Controllers\syllabi\syllabiController::class, 'destroy']);
});
Route::prefix('management')->group(function(){
    Route::put('add/lessons/{day}', [\App\Http\Controllers\General\ManagementController::class, 'addLessonsToDays']);
    Route::put('add/ClassroomToClass/{claass}', [\App\Http\Controllers\General\ManagementController::class, 'addClassroomToClass']);
    Route::put('add/classroom/{teacher}', [\App\Http\Controllers\General\ManagementController::class, 'addClassroomToTeacher']);
//    Route::post('customizeTeachForClassroom', [\App\Http\Controllers\General\ManagementController::class, 'customizeTeachForClassroom']);
    Route::put('add/subject/{teacher}', [\App\Http\Controllers\General\ManagementController::class, 'addSubjectToTeacher']);
    Route::post('subject/{class}', [\App\Http\Controllers\General\ManagementController::class, 'addSubjectToClass']);
    Route::get('/get-subjects', [\App\Http\Controllers\General\ManagementController::class, 'allSubjectsWithClasses']);
    Route::post('/get-classrooms/{class}', [\App\Http\Controllers\General\ManagementController::class, 'getClassroomAndTeacher']);
});

Route::prefix('resultant')->group(function () {
    Route::get('/{student}/{season}', [\App\Http\Controllers\Resultant\ResultantController::class, 'resultantStudent']);
});

Route::controller(AttendanceController::class)->prefix('attendance')->group(function () {
    Route::post('add', 'store');
    Route::post('get', 'getAttendance');
    Route::get('student/{student}', 'getAttendanceStudent');
});

Route::controller(\App\Http\Controllers\OnlineClassController::class)->prefix('onlineClass')->group(function () {
    Route::post('add', 'store');
    Route::get('teacher/{teacher}', 'teacherOnlineClass');
    Route::get('student/{class}/{classroom}', 'studentOnlineClass');
});


Route::controller(\App\Http\Controllers\TimeTableController::class)->prefix('timetable')->group(function (){
    Route::post('add',  'store');
    Route::get('studentTimetable/{class}/{classroom}',  'studentTimetable');
    Route::get('teacherTimetable/{teacher}',  'teacherTimetable');
    Route::get('mentorTimetable/{mentor}',  'mentorTimetable');


});

Route::get('all/{grade}/{day}/{lesson}', [\App\Http\Controllers\TimeTableController::class, 'show']);


///TODO:front

Route::get('alissar/{exam}', [\App\Http\Controllers\Exam\ExamController::class, 'mark_ladder']);


//Route::get('test', [\App\Http\Controllers\General\ManagementController::class, 'test']);

//Route::post('test/{teacher}', [\App\Http\Controllers\General\ManagementController::class, 'addSubjectToTeacher']);

//Route::prefix('season')->group(function(){
//Route::post('add', [\App\Http\Controllers\SeasonController::class, 'store']);
//Route::get('all', [\App\Http\Controllers\SeasonController::class, 'index']);
//Route::get('show/{season}', [\App\Http\Controllers\SeasonController::class, 'show']);
//Route::put('edit/{season}', [\App\Http\Controllers\SeasonController::class, 'update']);
//});


//use Illuminate\Support\Facades\Artisan;
//
//Route::get('/clear-cache', function() {
//
//    $configCache = Artisan::call('config:cache');
//    $clearCache = Artisan::call('cache:clear');
//});



//Route::prefix('schedule')->group(function () {
//    Route::get('quizzes/{claass}/{classroom}', [App\Http\Controllers\Classroom\ClassroomController::class, 'quizScheduleForClassroom']);
//});

