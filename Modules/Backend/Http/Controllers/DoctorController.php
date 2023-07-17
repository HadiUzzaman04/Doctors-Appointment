<?php

namespace Modules\Backend\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Backend\Entities\Department;

class DoctorController extends BaseController
{
    public function doctor()
    {
        $setTitle = __('Doctor');
        $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
        $departments = Department::all();
        $data = User::with('department:id,name')->where('role_id', '3')->orderBy('id', 'desc')->get();
        return view('backend::doctor', compact('data', 'departments'));
    }
    public function create(Request $request)
    {
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->department_id = $request->department_id;
        $data->qualification = $request->qualification;
        $data->fee = $request->fee;
        $data->details = $request->details;
        $data->password = $request->password;
        $data->status = $request->status;
        $data->role_id = $request->role_id;

        if ($request->hasfile('avatar')); {
            $file = $request->file('avatar');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('storage', $filename);
            $data->avatar = $filename;
        }
        $data->save();
        return redirect()->back();
    }
    public function delete($id)
    {
        User::find($id)->delete();
        return redirect()->back();
    }
    public function edit($id)
    {
        $setTitle = __(' Edit Doctor ');
        $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
        $departments = Department::all();
        $data = User::find($id);
        return view('backend::edit.editdoctor', compact('data', 'departments'));
    }
    public function update(Request $request, $id)
    {
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->department_id = $request->department_id;
        $data->qualification = $request->qualification;
        $data->fee = $request->fee;
        $data->details = $request->details;

        if ($request->File('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('storage', $filename);
            $data->avatar = $filename;
        } else {
            $data->avatar = $data->avatar;
        }
        $data->update();
        return redirect()->route('admin.doctor');
    }
    public function changestatus($id)
    {
        $getstatus = User::select('status')->where('id', $id)->first();
        if ($getstatus->status == 1) {
            $status = 2;
        } else {
            $status = 1;
        }
        User::where('id', $id)->update(['status' => $status]);
        return redirect()->back();
    }
}
