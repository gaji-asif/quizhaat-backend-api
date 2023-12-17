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
                $response = ['token' => $token];
                return response($response, 200);
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
            'name'=>$request['name'],
            'email'=>$request['email'],
            'phone'=>$request['phone'],
            'password'=> Hash::make($request['password'])
        ];
        $user = User::create($data); 
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['name'] =  $user->name;
        
        return response()->json(['success'=>$success], $this-> successStatus); 
    }
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 

        return response()->json(['success' => $user], $this-> successStatus); 
    } 

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
}