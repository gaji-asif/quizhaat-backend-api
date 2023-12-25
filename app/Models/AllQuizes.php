<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllQuizes extends Model
{
    use HasFactory;
    protected $table = 'all_quizes';
    protected $fillable = [
        'question_id',
        'date'
    ];
    function questionBnk(){
        return $this->belongsTo(QuestionBank::class,'question_id','id');
    }
}
