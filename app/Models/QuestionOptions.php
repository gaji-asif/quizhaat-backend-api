<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOptions extends Model
{
    use HasFactory;
    protected $table = 'sm_question_bank_mu_options';
    protected $fillable = [
        'title',
        'status',
        'active_status',
        'question_bank_id',
        'school_id',
    ];
    function questions(){
        return $this->belongsTo(QuestionBank::class,'question_bank_id','id');
    }
    function usersAnswer(){
        return $this->hasMany(UsersAnswer::class,'id','users_answer_id');
    }
}
