<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DailyQuizeController;
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
    Route::post('/update_user_profile', 'updateProfile');
});
Route::controller(BlogController::class)->group(function() {

    Route::get('/blog-short', 'blogShort');
    Route::get('/blog-details/{id}', 'blogDetails');
});
Route::get('/daily-quize',[DailyQuizeController::class,'dailyQuize']);
Route::get('/all-quiz-answer-list',[DailyQuizeController::class,'allQuizAnswerList']);
Route::get('/quiz-answer-giver-list/{id}',[DailyQuizeController::class,'QuizAnswerGiverList']);
Route::group(['middleware' => 'auth:api','controller'=>DailyQuizeController::class], function(){

    Route::post('/submit-quize-answer', 'dailyQuizeAnswerSubmit');
});
