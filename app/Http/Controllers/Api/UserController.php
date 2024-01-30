<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use App\Http\Requests\RegisterValidationRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller 
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request)
    { 
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
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(RegisterValidationRequest $request) 
    { 
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
        if(Auth::user()){
            $id = Auth::user()->id;
            $userData = User::find($id);
            
            return response()->json(
                [
                    'success'=>true,
                    'data'=>$userData
                ],
                 $this-> successStatus
            ); 
        }else{
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }

    }

    public function updateProfile(Request $request)
    {
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
    }

    // function dailyQuizeAnswerSubmit(Request $request)
    // {
    //     dd($request);
    // }
}