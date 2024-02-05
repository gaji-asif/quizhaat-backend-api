<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\Blogs;
use Carbon\Carbon;

class BlogController extends Controller
{
    /**
     * Get Shortened Blog  API.
     *
     * Retrieves a shortened version of blogs, including title, category, thumbnail, and creation date.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function blogShort()
    {
        try {
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
                $responseArray = [
                    'status_code' => 200,
                    'status_message' => 'OK',
                    'message' => 'details fetch successfully',
                    'is_data' => true,
                    'data' => $data
                ];

                return response()->json($responseArray); 
            }else{
                $responseArray = [
                    'status_code' => 204,
                    'status_message' => 'OK',
                    'message' => 'no blogs availaible',
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
        /**
     * Get Blog Details API.
     *
     * Retrieves detailed information about a specific blog post.
     *
     * @param  int  $id  The ID of the blog post.
     * @return \Illuminate\Http\JsonResponse
     */
    public function blogDetails($id)
    {
        try {
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
                $responseArray = [
                    'status_code' => 200,
                    'status_message' => 'OK',
                    'message' => 'details fetch successfully',
                    'is_data' => true,
                    'data' => $data
                ];
                return response()->json($responseArray); 
            }else{
                $responseArray = [
                    'status_code' => 204,
                    'status_message' => 'OK',
                    'message' => 'no blogs availaible',
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
}
