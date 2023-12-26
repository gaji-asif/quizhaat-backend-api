<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    use HasFactory;
    protected $table = 'blogs';
    protected $fillable = [
        'title',
        'blog_category_id',
        'thumbnail',
        'description',
        'is_published',
        'created_by',
    ];
    function BlogCategory(){
        return $this->belongsTo(BlogCategory::class,'blog_category_id','id');
    }
}
