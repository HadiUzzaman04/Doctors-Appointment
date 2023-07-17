<?php
namespace App\Http\Controllers\API\Admin;

use JWTAuthException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\APIController;

class AdminLoginController extends APIController
{
    
    public function login(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:30',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            $message = "Login Failed";
            return $this->sendResult($message,$data,$errors,$status);
        }
		$token = null;
		try {
		    if (!$token = $this->guard()->attempt($validator->validated())) {
                $status = false;
                $errors = [
                    "login" => "Invalid username or password",
                ];
                $message = "Login Failed";
		    }else{
                $message = "Login Successfull";
                $data = [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL(),
                    'user' => auth('api')->user(),
                ];
            }
		} catch (JWTAuthException $e) {
            $status = false;
		    $message = 'Failed to create token';
        }
        return $this->sendResult($message,$data,$errors,$status);
		// return $this->createNewToken($token);
    }

    public function adminProfile()
    {
        return response()->json(auth('api')->user());
    }

    
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth('api')->refresh());
    }

    public function guard()
    {
        return Auth::guard('api');
    }

    

}
