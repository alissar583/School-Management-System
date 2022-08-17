<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassClassroom;
use App\Models\Subject;
use App\Models\Syllabi;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\basicFunctionsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\generalTrait;
class TeacherController extends Controller
{
    use generalTrait, basicFunctionsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers=Teacher::query()->with([
            'gender',
            'religion',
            'address',
            'subjects'
        ])->get();
        return $this->returnAllData('teacher', $teachers,'success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $time = Carbon::now();

        $byte_array = $request->picture;
        $image = base64_decode($byte_array);
        Storage::put($time->format('Y').'/images/teacher/'. $request->f_name. '_'. $request->l_name. '/'. $request->l_name. '.jpg', $image);
        $picture = '/'. $time->format('Y').'/images/teacher/'. $request->f_name. '_'. $request->l_name. '/'. $request->l_name. '.jpg';

//        if ($request->hasFile('picture')) {
//            $picture = '/'.$request->file('picture')
//                    ->store($time->format('Y').'/images/teacher/'. $request->f_name. '_'. $request->l_name);
//        }
//        else{
//            $picture=null;
//        }

        $address = $this->addAddress($request);

            $teacher = Teacher::query()->create([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'code' =>'003',
                'picture' => $picture,
                'joining_date' => $request->joining_date,
                'salary' => $request->salary,
                'address_id' => (int)$address->id,
                'religion_id' =>(int) $request->religion_id,
                'gender_id' => (int)$request->gender_id,

            ]);

        $teacher->update([
            'code' =>  '003' . rand(0, 99) . $teacher->id . rand(100, 999) . $time->format('H') ,
        ]);
        $data = $teacher
            ->load( 'gender', 'religion', 'address',  'subjects');
        return $this->returnData('teacher', $data,'signup & add her / his subjects  successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        $data = $teacher
            ->load( 'gender', 'religion', 'address',  'subjects');
        return $this->returnData('teacher', $data,'success');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $picture=null;
        $time = Carbon::now();
//        if ($request->hasFile('picture')) {



//            $picture =  '/'.$request->file('picture')
//                    ->store($time->format('Y').'/images/teacher/'. $request->f_name. '_'. $request->l_name);
        if (isset($request->picture)){
            $byte_array = $request->picture;
            $image = base64_decode($byte_array);
            Storage::put($time->format('Y').'/images/teacher/'. $request->f_name. '_'. $request->l_name. '/'. $request->l_name. '.jpg', $image);
            $picture = '/'. $time->format('Y').'/images/teacher/'. $request->f_name. '_'. $request->l_name. '/'. $request->l_name. '.jpg';
            $teacher->update(['picture' => $picture]);
        }
//        }

        $address = $this->addAddress($request);

        $teacher->update([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
//            'picture' => $picture,
            'joining_date' => $request->joining_date,
            'salary' => $request->salary,
            'address_id' => (int) $address->id,
            'religion_id' => (int)$request->religion_id,
//            'gender_id' => (int)$request->gender_id,

        ]);
        $data = $teacher
            ->load( 'gender', 'religion', 'address', 'subjects');
        return $this->returnData('teacher', $data,'updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return $this->returnSuccessMessage('deleted teacher successfully');
    }

//    public function getTeacherWithSubjects(Teacher $teacher) {
//
//        $teacherWithSubjects  = $teacher->load('subjects');
//        $teacherSubjects = DB::table('teacher__subjects')->where('teacher_id', $teacher->id)->get();
//        foreach ($teacherSubjects as $teacherSubject) {
//            $classClassroom = DB::table('claass_classrooms')->where('id', $teacherSubject->class_classroom_id)->first();
//            $syllabi[] = DB::table('syllabi')->where('subject_id', $teacherSubject->subject_id)
//                ->where('class_id', $classClassroom->class_id)
//                ->get();
//        }
//        $data['teacher'] = $teacherWithSubjects;
//        $data['books'] = $syllabi;
//        return $this->returnData('data', $teacherWithSubjects, 'success');
//
//    }

    public function getTeacherWithClassroom(Teacher $teacher) {
        $subject = $teacher->subjects()->first();
        $classes = TeacherSubject::query()->where('teacher_id', $teacher->id)->where('subject_id', $subject->id)->get();
//        return $ss;
        foreach ($classes as $class) {
            $classClassrooms[] = ClassClassroom::query()->where('id', $class->class_classroom_id)->first()->load('classes', 'classrooms');
        }
        $data['subject'] = $subject;
        $data['classes'] = $classClassrooms;
//        return $data;
        return $this->returnData('data', $data, 'successs');
    }




}
