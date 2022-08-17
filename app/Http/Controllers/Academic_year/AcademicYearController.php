<?php

namespace App\Http\Controllers\Academic_year;

use App\Http\Controllers\Controller;
use App\Models\Academic_year;
use App\Models\Student;
use App\Traits\generalTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AcademicYearController extends Controller
{
 use generalTrait;
    public function index(Request $request)
    {

//        $path = 'C:\Users\ِAbdUlrahem\Desktop\1.jpg';
//        $type = pathinfo($path, PATHINFO_EXTENSION);
//        $data = file_get_contents($path);
//        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
//        return $base64;


        //Here write your code to get $byte_array
        $byte_array = $request->photo;
        $data = base64_decode($byte_array);
        $s = Storage::put('abd1.jpg', $data);
//        $s = Storage::get()
        return $s;
//        $im = imagecreatefromstring($data);
//       return $data;
//        $academicYears = Academic_year::query()->get();
//
//        return $this->returnAllData('Academic Years', $academicYears, 'List Of Academic Years');
//        return $this->returnData('Academic Years', $academicYears, 'List Of Academic Years');


    }


    public function store(Request $request)
    {
        $academicYear = Academic_year::query()->create([
            'date' => $request->date,
        ]);
        return $this->returnData('Academic Year', $academicYear, 'Added Successfully');
    }


    public function show(Academic_year $yearId)
    {
        return $this->returnData('Academic Year', $yearId, 'success');
    }


    public function update(Request $request, Academic_year $yearId)
    {
        $yearId->update([
            'date' => $request->date,
        ]);
        return $this->returnData('Academic Year', $yearId, 'Updated Successfully');
    }


    public function destroy(Academic_year $yearId)
    {
        $yearId->delete();
        return $this->returnSuccessMessage('Deleted Successfully');
    }
}
