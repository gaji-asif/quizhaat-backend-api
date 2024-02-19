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
        /**
     * Login API.
     *
     * Authenticates a user based on provided credentials.
     *
     * @param  \Illuminate\Http\Request  $request  The request body containing user credentials.
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    { 
        //echo "test"; exit;
        // $user = User::where('email', $request->email)->first();
        // if ($user) {
        //     if (Hash::check($request->password, $user->password)) {
        //         $token =  $user->createToken('MyApp')-> accessToken;
        //         $response = ['token' => $token, 'username'=>$user->username, 'userid'=>$user->id];
        //         return response($response, 200);
        //     } else {
        //         $response = ["message" => "Password mismatch"];
        //         return response($response, 422);
        //     }
        // } else {
        //     $response = ["message" => 'User does not exist'];
        //     return response($response, 422);
        // }

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
     * Register API.
     *
     * Creates a new user account based on the provided registration data.
     *
     * @param  \App\Http\Requests\RegisterValidationRequest  $request  The validated request containing user registration data.
     * @return \Illuminate\Http\JsonResponse
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
        //dd($user);
        //$success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['username'] = $request['username'];
        $success['userid'] = $user->id;
        
        return response()->json(['success'=>$success], 200); 

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
     * Logout API.
     *
     * Revokes the access token for the authenticated user, effectively logging them out.
     *
     * @param  \Illuminate\Http\Request  $request  The authenticated user's request.
     * @return \Illuminate\Http\Response
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
     * Update Profile API.
     *
     * Updates the profile information of the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request  The request containing updated user profile data.
     * @return \Illuminate\Http\JsonResponse
     */
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
                    $responseArray = [
                        'status_code' => 200,
                        'status_message' => 'OK',
                        'message' => 'Profile Updated Successfully',
                        'is_data' => true,
                    ];
                    return response()->json($responseArray);
                }
            }else{
                $responseArray = [
                    'status_code' => 422,
                    'status_message' => 'OK',
                    'message' => 'User does not exist',
                    'is_data' => false,
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