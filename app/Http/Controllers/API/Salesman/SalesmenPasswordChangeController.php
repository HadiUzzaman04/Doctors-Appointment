<?php

namespace App\Http\Controllers\API\Salesman;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\APIController;

class SalesmenPasswordChangeController extends APIController
{
    public function change_password(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;

        $validator = Validator::make($request->all(), [
            'current_password'      => 'required|string|min:8',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            $status  = false;
            $errors  = $validator->errors();
            $message = "Validation Errors";
            return $this->sendResult($message,$data,$errors,$status);
        }else{
            DB::beginTransaction();
            try {
                $user = auth()->user();

                if (!Hash::check($request->current_password, $user->password)) {
                    $status = false;
                    $message = 'Current password does not match';
                }else{
                    $user->password = $request->password;
                    if($user->update()){
                        $status = true;
                        $message = 'Password Changed Successfully';
                    }else{
                        $status = false;
                        $message = 'Failed to change password. Try Again!';
                    }
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $status = false;
                $message = $e->getMessage();
            }
            return $this->sendResult($message,$data,$errors,$status);
        }
    }
}
