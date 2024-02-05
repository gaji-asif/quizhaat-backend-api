<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AllQuizes;
use App\Models\QuestionBank;
use App\Models\QuestionOptions;
use App\Models\UsersAnswer;
use App\Models\Subjects;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Log;

class DailyQuizController extends Controller
{
    /**
     * Get Daily Quiz API.
     *
     * Retrieves information about the daily quiz, including the question, options, and related details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dailyQuize()
    {
        try {
            $dailyQuize = AllQuizes::latest()->first();
            $datetime = $dailyQuize->created_at;
            $dateOnly = Carbon::parse($datetime)->toDateString();
            $today = Carbon::today()->toDateString();
            $optios = QuestionOptions::where('question_bank_id',$dailyQuize->questionBnk->id)->get();
            $optionData = [];
            foreach ($optios as $option){
                $optionData[] = [
                'option_id'=> $option->id,
                'option'=> $option->title,
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
                $responseArray = [
                    'status_code' => 200,
                    'status_message' => 'OK',
                    'message' => 'Data fetch successfully',
                    'is_data' => true,
                    'data' => $data
                ];
                return response()->json($responseArray); 
            }else{
                $responseArray = [
                    'status_code' => 200,
                    'status_message' => 'OK',
                    'message' => 'no quize for today',
                    'is_data' => false,
                    'data' => []
                ];
                return response()->json($responseArray);
            }
        } catch (\Exception $th) {
            return response([
             'status_code' => 500,
             'status_message' => 'error',
             'message' => $th->getMessage(),
             'is_data' => false,
             'data' => []
          ]);
         } 
    }

    public function dailyQuizeAnswerSubmit(Request $request)
    {
        dd($request);
            $setUserAnswerId = $request->input('set_user_answer_id');
            $quistionId = $request->input('quistion_id');
            $userID = $request->input('userID');
    
            // Now you have the values, do whatever you want with them
            dd($setUserAnswerId, $quistionId, $userID);
      
    }

    // public function allQuizAnswerList()
    // {
    //     $questionsWithCorrectAnswers = UsersAnswer::where('is_right', 0)
    //     ->with('question') // Load the question relationship
    //     ->get()
    //     ->groupBy('question.question') // Group by question name
    //     ->map(function ($groupedItems) {
    //         $question_name = $groupedItems[0]->question->question; // Get the question name
    //         $answersData = $groupedItems->map(function ($item) {
    //             return [
    //                 'user_answer_id' => $item->id,
    //                 'user_id' => $item->user_id,
    //             ];
    //         })->toArray(); // Get an array of user_answer_id and user_id pairs

    //         $total_right_answers = $groupedItems->count(); // Count the number of correct answers

    //         // Select a random winner index for the current question
    //         // $winnerIndex = random_int(0, $total_right_answers - 1);

    //         // Get the randomly selected winner's user_answer_id and user_id
    //         // $winnerData = $answersData[$winnerIndex] ?? null;

    //         return [
    //             'question_name' => $question_name,
    //             'total_right_answers' => $total_right_answers,
    //             // 'winner_user_id' => $winnerData['user_id'] ?? null, // Winner's user ID
    //             // 'winner_user_answer_id' => $winnerData['user_answer_id'] ?? null, // Winner's user_answer_id
    //         ];
    //     });

    // return $questionsWithCorrectAnswers;

    
    // }

    // public function selectWinnerUserIdForQuestion($answersForQuestion)
    // {
    //     $totalCorrectAnswers = $answersForQuestion->count();
    
    //     if ($totalCorrectAnswers === 0) {
    //         return null; // No correct answers found for this question
    //     }
    
    //     // Generate a random winner index within the range of total correct answers
    //     $randomWinnerIndex = random_int(0, $totalCorrectAnswers - 1);
    
    //     // Get the randomly selected winner user ID
    //     $winnerUserId = $answersForQuestion[$randomWinnerIndex]->user_id;
    
    //     // Log the randomly generated winner user ID for verification
    //     Log::info("Randomly generated winner user ID: $winnerUserId");
    
    //     return $winnerUserId;
    // }
    /**
     * Get All Quiz Answer List API.
     *
     * Retrieves information about the total correct answers for each quiz based on user responses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allQuizAnswerList()
    {
        try {
            $quizzes = AllQuizes::with('questionBnk')->get();
    
            $correctAnswersByQuiz = $quizzes->mapWithKeys(function ($quiz) {
                $correctAnswersCount = UsersAnswer::where('question_id', $quiz->question_id)
                    ->where('is_right', 1)
                    ->count();
                return [
                    $quiz->id => [
                        'quiz_number' => $quiz->id,
                        'total_correct_answers' => $correctAnswersCount,
                    ]
                ];
            });
            $responseArray = [
                'status_code' => 200,
                'status_message' => 'OK',
                'message' => 'details fetch successfully',
                'is_data' => true,
                'data' => $correctAnswersByQuiz
            ];
        
            return $responseArray;
        } catch (\Exception $th) {
            return response([
             'status_code' => 500,
             'status_message' => 'error',
             'message' => $th->getMessage(),
             'is_data' => false,
             'data' => []
          ]);
         } 
    }  
    /**
     * Get Quiz Answer Giver List API.
     *
     * Retrieves a list of users who gave correct answers for a specific quiz.
     *
     * @param  int  $id  The ID of the quiz.
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function QuizAnswerGiverList($id)
    {
        try {
            $quiz = AllQuizes::with('questionBnk')->find($id);

            if (!$quiz) {
                $responseArray = [
                    'status_code' => 422,
                    'status_message' => 'OK',
                    'message' => 'Quiz not found',
                    'is_data' => false,
                    'data' => []
                ];
                return response()->json($responseArray);
            }

            $correctAnswerUsers = UsersAnswer::where('question_id', $quiz->question_id)
                ->where('is_right', 1)
                ->with('user') 
                ->get();
            $correctAnswerUsersData = $correctAnswerUsers->map(function ($answer) {
                return [
                    'user_id' => $answer->user->id,
                    'full_name' => $answer->user->full_name,
                ];
            });
            $responseArray = [
                'status_code' => 200,
                'status_message' => 'OK',
                'message' => 'details fetch successfully',
                'is_data' => true,
                'data' => $correctAnswerUsersData
            ];

            return response()->json($responseArray);
        } catch (\Exception $th) {
            return response([
            'status_code' => 500,
            'status_message' => 'error',
            'message' => $th->getMessage(),
            'is_data' => false,
            'data' => []
        ]);
        } 
        
    }   
}
