<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Paarent;
use App\Traits\generalTrait;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    use generalTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Paarent $parent)
    {
        return $this->returnData('parent', $parent, 'success');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paarent $parent)
    {
        $parent->update([
            'mother_name' => $request->mother_name,
            'father_name' => $request->father_name,
            'national_number' => $request->national_number,
            'phone' => $request->parentPhone,
            'email' => $request->parentEmail,
            'jop' => $request->parentJop,
        ]);

        return $this->returnData('Parent', $parent, 'updated parent successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paarent $parent)
    {
        $parent->delete();
        return $this->returnSuccessMessage('deleted parent successfully');
    }

    public function getParentWithChild(Paarent $parent) {
        return $this->returnData('parent', $parent->load('child'), 'success');
    }
}
