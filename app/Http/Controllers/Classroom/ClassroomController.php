<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Models\Claass;
use App\Models\ClassClassroom;
use App\Models\Classroom;
use App\Models\Quiz;
use App\Models\TeacherSubject;
use App\Traits\basicFunctionsTrait;
use App\Traits\generalTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Sodium\increment;

class ClassroomController extends Controller
{
    use generalTrait,  basicFunctionsTrait;

    public function index()
    {
        $classrooms = Classroom::query()->get();
        return $this->returnAllData('classroomms', $classrooms, 'all classroom');

//        return $this->returnData('classroomms', $classrooms, 'all classroom');
    }


    public function store(Request $request)
    {
        foreach($request->classroom as $classroom){
            $classroomNumber = Classroom::query()->latest('name')->first('name');

            $newclassroom = Classroom::query()->create([
                'name' => $classroomNumber->name + 1,
                'max_number' => (int)$classroom['max_number'],
            ]);

            $newclassroom ->class()->syncWithoutDetaching($classroom['class_id']);

        }

        return  $this->returnData('classroom', $newclassroom, 'added classroom & choose the classes which belongto successfully');

    }

    public function update(Request $request, Classroom $classroom)
    {
        $classroom->update([
            'max_number' => $request->max_number,
        ]);
        return  $this->returnData('classroom', $classroom, 'updated classroom successfully');

    }

    public function destroy(Request $request)
    {
        Classroom::query()->find($request->classroom_id);
        Claass::query()->find($request->class_id);
        $check = $this->checkClassClassroom($request->class_id, $request->classroom_id);
        if ($check == null) {
            return $this->returnErrorMessage('input error', 400);
        }
        ClassClassroom::query()->where('id', $check->id)->delete();
        return $this->returnSuccessMessage('deleted classroom successfully');
    }

}
