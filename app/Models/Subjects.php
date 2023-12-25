<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    use HasFactory;
    protected $table = 'sm_subjects';
    protected $fillable = [
        'subject_name',
        'subject_code',
        'subject_type',
        'active_status'
    ];
    function subject()
    {
        return $this->belongsTo(QuestionBank::class,'id','subject_id');
    }
}
