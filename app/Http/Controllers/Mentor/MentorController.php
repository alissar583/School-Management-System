<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\ClassClassroom;
use App\Models\Mentor;
use App\Traits\basicFunctionsTrait;
use App\Traits\generalTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    use generalTrait, basicFunctionsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mentors = Mentor::query()->get();
        return $this->returnAllData('mentor', $mentors, 'list of mentors');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $address = $this->addAddress($request);
        $mentor = Mentor::query()->create([
            'email' => $request->email,
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'code' =>  '004',
            'address_id' =>(int) $address->id,
            'joining_date' => $request->joining_date,
            'phone' => $request->phone,
            'class_id' => (int)$request->class_id,
        ]);
        $time = Carbon::now();
        $mentor->update([
            'code' =>  '004' .$mentor->class_id.  rand(0, 99) . $mentor->id . rand(100, 999) . $time->format('H') ,
        ]);

        return $this->returnData('mentor', $mentor->load('class', 'address'), 'added mentor successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Mentor $mentor)
    {
        return $this->returnData('mentor', $mentor,'success');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mentor $mentor)
    {
        $address = $this->addAddress($request);
        $mentor->update([
            'email' => $request->email,
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'address_id' => (int)$address->id,
            'joining_date' => $request->joining_date,
            'phone' => $request->phone,
            'class_id' =>(int) $request->class_id,
        ]);

        return $this->returnData('mentor', $mentor,'updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mentor $mentor)
    {
        $mentor->delete();
        return $this->returnSuccessMessage('deleted mentor successfully');
    }

    public function getStudents(Mentor $mentor) {
        $classrooms = ClassClassroom::query()->where('class_id', $mentor->class_id)->with(['classrooms', 'students'])->get();
        return $this->returnAllData('data', $classrooms, 'success');
    }

    public function getClassrooms(Mentor $mentor) {
        $classrooms = $mentor->class()->with('classroom')->get();
        return $this->returnAllData('data', $classrooms, 'success');
    }
}
