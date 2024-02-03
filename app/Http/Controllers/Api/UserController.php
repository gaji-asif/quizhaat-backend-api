<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use App\Http\Requests\RegisterValidationRequest;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller 
{
    public $successStatus = 200;
    /** 
     * login api 
     * @param reqiest body from user
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request)
    { 
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token =  $user->createToken('MyApp')-> accessToken;
                    $data = [
                        'token' => $token,
                        'username'=>$user->username, 
                        'userid'=>$user->id
                    ];
                    $responseArray = [
                        'status_code' => 200,
                        'status_message' => 'OK',
                        'message' => 'login successful',
                        'is_data' => true,
                        'data' => $data
                    ];
                    return response($responseArray);
                } else {
                    $responseArray = [
                        'status_code' => 422,
                        'status_message' => 'not ok',
                        'message' => 'Password mismatch',
                        'is_data' => false,
                        'data' => []
                    ];
                    return response($responseArray);
                }
             } else {
                $responseArray = [
                    'status_code' => 422,
                    'status_message' => 'not ok',
                    'message' => 'User does not exist',
                    'is_data' => false,
                    'data' => []
                ];
                return response($responseArray);
             }
        } catch (\Throwable $th) {
            throw $th;
        }       
       
    }
    /** 
     * Register api 
     * @param $request
     * @return \Illuminate\Http\Response 
     */ 
    public function register(RegisterValidationRequest $request) 
    { 
        try {
            $data = [
                'full_name'=>$request['full_name'],
                'username'=>$request['username'],
                'email'=>$request['email'],
                'phone'=>$request['phone'],
                'password'=> Hash::make($request['password']),
                'usertype'=>1
            ];
            $user = User::create($data); 
            $token =  $user->createToken('MyApp')-> accessToken;
            $responseData = [
                'token' => $token,
                'username'=>$user->username, 
                'userid'=>$user->id
            ];
            $responseArray = [
                'status_code' => 200,
                'status_message' => 'OK',
                'message' => 'registration successful',
                'is_data' => true,
                'data' => $responseData
            ];
            
            return response()->json($responseArray); 
        } catch (\Throwable $th) {
            throw $th;
        }
    } 
    /** 
     * logout api 
     * 
     */ 
    public function logout(Request $request)
    {
        $token = $request->user()->token();

        if ($token) {
            $token->revoke();
            $response = ['message' => 'You have been successfully logged out!'];
        } else {
            $response = ['message' => 'UnAuthorized!'];
        }

        return response($response, 200);
    }
    /** 
     * User Details 
     * 
     */ 
    public function userDetails()
    {
        try {
            if(Auth::user()){
                $id = Auth::user()->id;
                $userData = User::find($id);
                $responseArray = [
                    'status_code' => 200,
                    'status_message' => 'OK',
                    'message' => 'details fetch successfully',
                    'is_data' => true,
                    'data' => $userData
                ];
                
                return response()->json($responseArray); 
            }else{
                $response = ["message" => 'User does not exist'];
    
                return response($response, 422);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            if(Auth::user()){
                $id = Auth::user()->id;
                $userData = User::find($id);
                $data = [
                    'full_name'=>$request['full_name'],
                    'username'=>$request['username'],
                    'email'=>$request['email'],
                    'phone'=>$request['phone'],
                    'usertype'=>1,
                     
                ];
                if($request->file('image')){
                    $image = $request->file('image');
                    $path = public_path('user_image/');
                
                    $imageName = $request['username'].time() . '.' . $image->extension();
                    $image->move($path, $imageName);
                   $data['image'] = $imageName;
                }
                $result = $userData->update($data);
                if($result){
                    return response()->json(
                        [
                            'success'=>true,
                            'message'=>"Profile Updated Successfully"
                        ],
                         $this-> successStatus
                    ); 
                }
            }else{
                $response = ["message" => 'User does not exist'];
                return response($response, 422);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}