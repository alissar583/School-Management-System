<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Traits\generalTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SeasonController extends Controller
{
    use generalTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seasons = DB::table('seasons')->get();
        return $this->returnAllData('seasons', $seasons, 'all seasons');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $season=Season::query()->create([
            'name'=>$request->name
        ]);

        return $this->returnData('season', $season, 'add season successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Season $season)
    {
       $season_exam = $season->Exam()->first();
       $season_quize = $season->Quize()->get();
       $data['season_exam']=  $season_exam;
       $data['season_quize']=  $season_quize;
       return $this->returnData('data', $data, 'success');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Season $season)
    {
        $season->update([
            'name'=>$request->name
        ]);

        return $this->returnData('season', $season, 'update season successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
