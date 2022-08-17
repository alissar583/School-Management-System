<?php

namespace App\Http\Controllers\Exam;
use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Question;
use App\Traits\generalTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class ChoiseController extends Controller
{
    use generalTrait;
    public function index()
    {
        $choices=Choice::query()->with('question')->get();
        return $this->returnAllData('choices', $choices, 'all choices');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Question $question)
    {
        $arrayChioces = $request->chioces;
        $choices = DB::table('choices')->select('status')
            ->where('question_id', $question->id)
            ->where('status', true)
            ->first();
        foreach ($arrayChioces as $item) {
            if (isset($choices)) {
                if ($item['status'] == true) {
                    return $this->returnErrorMessage('must enter one correct answer for this question', 400);
                }
            }
            $choice = Choice::query()->create([
                'text' => $item['text'],
                'status' => $item['status'],
                'question_id' => $question->id
            ]);
        }

        return  $this->returnData('choices', $choice, 'added choices successfully');

    }

    public function update(Request $request,Choice $choice)
    {
        if ($request->status == true) {
            $choices = Choice::query()
                ->where('question_id', $choice->question_id)
                ->where('status', true)
                ->first();

            if (isset($choices)) {
                return $this->returnErrorMessage('must enter one correct answer for this question', 400);
            }
        }

       $choice->update([
        'text'=> $request->text,
        'question_id' => $request->question_id,
        'status'=> $request->status,
       ]);

        return  $this->returnData('choice', $choice, 'updated choice successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Choice $choice)
    {
        $choice->delete();
        return $this->returnSuccessMessage('deleted choice successfully');

    }
}
