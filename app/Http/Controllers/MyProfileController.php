<?php

namespace App\Http\Controllers;

use Auth, Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\ProfileUpdateFormRequest;
use App\Http\Requests\PasswordUpdateFormRequest;

class MyProfileController extends BaseController
{
    use UploadAble;

    public function index()
    {
        $this->setPageData('My Profile','My Profile','far fa-id-badge');
        return view('user.profile');
    }

    public function updateProfile(ProfileUpdateFormRequest $request)
    {
        if($request->ajax())
        {
            if(auth()->check())
            {
                $collection = collect($request->validated())->except(['username','avatar']);
                $collection = $this->track_data($collection,$request->update_id);
                $avatar     = !empty($request->old_avatar) ? $request->old_avatar : null;
                if($request->hasFile('avatar')){
                    $avatar  = $this->upload_file($request->file('avatar'),USER_PHOTO_PATH);
                    if(!empty($request->old_avatar)){
                        $this->delete_file($request->old_avatar, USER_PHOTO_PATH);
                    }  
                }
                $collection = $collection->merge(compact('avatar'));
                $result     = User::updateOrCreate(['id'=>auth()->user()->id],$collection->all());
                $output     = $this->store_message($result, auth()->user()->id);
                if(empty($result))
                {
                    if($request->hasFile('avatar')){
                        $this->delete_file($avatar, USER_PHOTO_PATH);
                    }
                }
                return response()->json($output);
            }
            
        }
    }

    public function updatePassword(PasswordUpdateFormRequest $request)
    {
        if($request->ajax())
        {
            if(auth()->check())
            {
                $user = Auth::user();

                if (!Hash::check($request->current_password, $user->password)) {
                    $output = ['status'=>'error','message'=>'Current password does not match!'];
                }else{
                    $user->password = $request->password;
                    if($user->save()){
                        $output = ['status'=>'success','message'=>'Password changed successfully'];
                    }else{
                        $output = ['status'=>'error','message'=>'Failed to change password. Try Again!'];
                    }
                }
                return response()->json($output);
            }
            
        }
    }


}
