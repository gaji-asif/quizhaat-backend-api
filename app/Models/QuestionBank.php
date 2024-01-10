<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;
    protected $table = 'sm_question_banks';
    protected $fillable = [
        'subject_id',
        'type',
        'question',
        'marks',
        'trueFalse',
        'suitable_words',
        'number_of_option',
        'active_status',
        'q_group_id',
        'class_id',
        'section_id',
        'school_id',
    ];
    function questionBnk(){
        return $this->hasMany(AllQuizes::class, 'id', 'question_id');
    }
    function questionOption(){
        return $this->hasMany(QuestionOptions::class, 'id', 'question_bank_id');
    }
    function subject(){
        return $this->belongsTo(Subjects::class, 'subject_id', 'id');
    }
    function questionAnswer(){
        return $this->hasMany(UserAnswer::class, 'id', 'question_id');
    }
}
