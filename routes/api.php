<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DailyQuizController;
use App\Http\Controllers\Api\BlogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login', [UserController::class,'login']);
Route::post('/register', [UserController::class,'register']);
Route::group(['middleware' => 'auth:api','controller'=>UserController::class], function(){

    Route::post('/logout', 'logout');
    Route::get('/user_profile', 'userDetails');
    Route::PUT('/update_user_profile', 'updateProfile');
});
Route::controller(BlogController::class)->group(function() {

    Route::get('/blog-short', 'blogShort');
    Route::get('/blog-details/{id}', 'blogDetails');
});
Route::get('/daily-quiz',[DailyQuizController::class,'dailyQuize']);
Route::get('/all-quiz-answer-list',[DailyQuizController::class,'allQuizAnswerList']);
Route::get('/quiz-answer-giver-list/{id}',[DailyQuizController::class,'QuizAnswerGiverList']);
Route::group(['middleware' => 'auth:api', 'cors','controller'=>DailyQuizController::class], function(){

    Route::post('/submit-quiz-answer', 'dailyQuizeAnswerSubmit');
});
