<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AllQuizes;
use App\Models\QuestionBank;
use App\Models\QuestionOptions;
use App\Models\Subjects;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 

class DailyQuizeController extends Controller
{
    public $successStatus = 200;
    /** 
     * dailyQuize api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function dailyQuize()
    {
        $dailyQuize = AllQuizes::latest()->first();
        $datetime = $dailyQuize->created_at;
        $dateOnly = Carbon::parse($datetime)->toDateString();
        $today = Carbon::today()->toDateString();
        $optios = QuestionOptions::where('question_bank_id',$dailyQuize->questionBnk->id)->get();
        $optionData = [];
        foreach ($optios as $option){
            $optionData[] = [
              'option_id'=> $option->id,
              'options'=> $option->title,
            ];
        }
        if($dateOnly == $today){
            $date = Carbon::parse($dateOnly);
            $data = [
                'question_id' => $dailyQuize->questionBnk->id,
                'subject' => $dailyQuize->questionBnk->subject->subject_name,
                'question' => $dailyQuize->questionBnk->question,
                'options' => $optionData,
                'date' => $date->format('jS F Y'),
                'question_type' => count($optionData)==4 ? 'multiple option quistion' : 'true false type',
            ];
            return response()->json(['data'=>$data], $this-> successStatus); 
        }else{
            return response()->json(['message'=>'no quize for today'], $this-> successStatus); 
        }
    }

    public function dailyQuizeAnswerSubmit(Request $request)
    {
        if(Auth::user()){
          dd($request);
        }else{
            dd('please make sure you loged in first');
        }
       
    }
}
