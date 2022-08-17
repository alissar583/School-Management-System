<?php

namespace App\Http\Controllers\syllabi;

use App\Http\Controllers\Controller;
use App\Models\Claass;
use App\Models\Subject;
use App\Models\Subject_Class;
use App\Models\SubjectClass;
use App\Models\Syllabi;
use App\Models\Teacher;
use App\Traits\basicFunctionsTrait;
use App\Traits\generalTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class syllabiController extends Controller
{
    use generalTrait, basicFunctionsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Claass $class)
    {
        $syllabi = Syllabi::query()
            ->where('class_id', $class->id)
            ->where('active', 1)->get();
        return $this->returnAllData('syllabi', $syllabi, 'syllabi');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $path  = null;
        $subName = Subject::query()->where('id', $request->subject_id)->first();
        $classNmae = Claass::query()->where('id', $request->class_id)->first();
        if (! isset($subName) || ! isset($classNmae)) {
            return $this->returnErrorMessage('input error', 400);
        }
        $check = $this->checkHasRelationBetweenClassAndSubject($classNmae->id, $subName->id);
        if (!isset($check)) {
            return $this->returnErrorMessage('there is not relationship between subject and class', 404);
        }
        $newClassName = str_replace(' ', '_', $classNmae->name);

        $time  = Carbon::now();
        if ($request->hasFile('content')) {
            $path = '/'.$request->file('content')
                    ->store($time->format('Y').'/syllabi/'.$subName->name. '_'. $newClassName);
        }

        $syllabi = Syllabi::query()->create([
            'content' => $path,
            'class_id' => (int)$request->class_id,
            'active' => 0,
            'subject_id' => (int)$request->subject_id,
        ]);
        return $this->returnData('syllabi', $syllabi->load('subject', 'class'), 'added syllabi success');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Syllabi $syllabi)
    {
        $pdf  = null;
        $time  = Carbon::now();
        if ($request->hasFile('content')) {
                if (Storage::exists($syllabi->content)) {
                    Storage::delete($syllabi->content);
//                    Storage::deleteDirectory($time->format('Y').'/syllabi/student/');
                    }
            $pdf = '/'.$request->file('content')
                    ->store($time->format('Y').'/syllabi/subject/');
        }

        $syllabi->update([
            'content' => $pdf,
            'subject_class_id' => $request->subject_class_id
        ]);
        return $this->returnData('syllabi', $syllabi, 'updated syllabi success') ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Syllabi $syllabi)
    {
        $syllabi->delete();
        return  $this->returnSuccessMessage('deleted success');
    }
}
