<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\Controller;
use App\Models\Claass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Traits\generalTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    use generalTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::query()->get();
        return $this->returnAllData('subjects', $subjects, 'all subjects');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subject = Subject::query()
            ->where('name', $request->subject_name)->first();
        if (!isset($subject)) {
            $subject = Subject::query()->create([
                'name' => $request->subject_name
            ]);
        }

        foreach($request->class_id as $key => $insert){

            DB::table('subject_mark')->insert([
                'class_id' => $request->class_id[$key],
                'mark' => $request->mark[$key],
                'subject_id' =>$subject->id,
            ]);


//            foreach($request->syllabiContent[$key] as $key1 => $insert1){
//
//
//                $paths = $request->file('syllabiContent.'.[$key][$key1]);
//
//                 foreach($paths as $path){
//
//                  $a = $path->store($time->format('Y').'/syllabi/'.$subject->name. '/'. $key1);
//
//
//                    DB::table('syllabi')->insert([
//                        'class_id' => $request->class_id[$key],
//                        'content' => $a,
//                        'subject_id' =>$subject->id,
//                    ]);
//                }
//
//                return $a;


                // foreach($paths as $path){

                //    $path->store($time->format('Y').'/syllabi/'.$subject->name. '/'. $classNmae->id);

                //     DB::table('syllabi')->insert([
                //         'class_id' => $request->class_id[$key],
                //         'content' => $path,
                //         'subject_id' =>$subject->id,
                //     ]);

                // }


                // if ($request->hasFile('syllabiContent[$key][$key1]')) {
                // }


            }
//        }

             return $this->returnSuccessMessage('add subject & choose its class&sylabil successfully');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $subject->update([
            'name' => $request->subject_name
        ]);
        return $this->returnData('subject', $subject, 'updated subject successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return $this->returnSuccessMessage('deleted subject successfully');
    }


}
