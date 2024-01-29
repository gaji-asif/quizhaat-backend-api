<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\Blogs;
use Carbon\Carbon;

class BlogController extends Controller
{
    public $successStatus = 200;

    public function blogShort()
    {
        $blogs = Blogs::select('id','title','thumbnail','blog_category_id','created_at')->get();
        $data = [];
        foreach ($blogs as $blog){
            $createdDate = $blog->created_at;
            $dateOnly = Carbon::parse($createdDate)->toDateString();
            $date = Carbon::parse($dateOnly);
            $data[] = [
                'id'=>$blog->id,
                'category'=>$blog->BlogCategory->name,
                'category'=>$blog->BlogCategory->name,
                'blogtitle'=>$blog->title,
                'thumbnail'=>$blog->thumbnail,
                'date'=>$date->format('jS F Y'),
            ];
        }
    
        if(!empty($data)){
            return response()->json(['blogs'=>$data], $this-> successStatus); 
        }else{
            return response()->json(['message'=>'no blogs availaible'], 204); 
        }
    }

    public function blogDetails($id)
    {
        $blog = Blogs::find($id);
        $createdDate = $blog->created_at;
        $dateOnly = Carbon::parse($createdDate)->toDateString();
        $date = Carbon::parse($dateOnly);
            $data = [
                'id'=>$id,
                'category'=>$blog->BlogCategory->name,
                'blogtitle'=>$blog->title,
                'thumbnail'=>$blog->thumbnail,
                'details'=>$blog->description,
                'is_published'=>$blog->is_published,
                'created_by'=>$blog->created_by,
                'date'=>$date->format('jS F Y'),
            ];
        if(!empty($blog)){
            return response()->json(['blogs'=>$data], $this-> successStatus); 
        }else{
            return response()->json(['message'=>'no blogs availaible'], 204); 
        }
    }
}
