<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersAnswer extends Model
{
    use HasFactory;
    protected $table = 'users_answers';
    protected $fillable = [
        'user_id',
        'question_id',
        'users_answer_id',
        'is_right',
        'is_winner',
    ];

    function question()
    {
        return $this->belongsTo(QuestionBank::class,'question_id','id');
    }
    function option()
    {
        return $this->belongsTo(QuestionOptions::class,'users_answer_id','id');
    }
}
