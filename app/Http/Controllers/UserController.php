<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserFormRequest;
use App\Http\Controllers\BaseController;
use App\Models\Role;

class UserController extends BaseController
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('user-access')){
            $this->setPageData('User','User','fas fa-users',[['name' => 'User']]);
            $data = [
                'roles'     => Role::toBase()->where('id','!=',1)->orderBy('id','asc')->get(),
                'deletable' => self::DELETABLE
            ];
            return view('user.index',$data);
        }else{
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if($request->ajax()){
            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }
            if (!empty($request->username)) {
                $this->model->setUsername($request->username);
            }
            if (!empty($request->phone)) {
                $this->model->setPhone($request->phone);
            }
            if (!empty($request->email)) {
                $this->model->setEmail($request->email);
            }
            if (!empty($request->role_id)) {
                $this->model->setRoleID($request->role_id);
            }
            if (!empty($request->status)) {
                $this->model->setStatus($request->status);
            }

            $this->set_datatable_default_properties($request);//set datatable default properties
            $list = $this->model->getDatatableList();//get table data
            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('user-edit')){
                $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.self::ACTION_BUTTON['Edit'].'</a>';
                }
                if(permission('user-view')){
                $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '">'.self::ACTION_BUTTON['View'].'</a>';
                }
                if(permission('user-delete')){
                    if($value->deletable == 2){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">'.self::ACTION_BUTTON['Delete'].'</a>';
                    }
                }


                $row = [];
                if(permission('user-bulk-delete')){
                    $row[] = ($value->deletable == 2) ? row_checkbox($value->id) : '';//custom helper function to show the table each row checkbox
                }
                $row[] = $no;
                $row[] = $this->table_image(USER_PHOTO_PATH,$value->avatar,$value->name,$value->gender);
                $row[] = $value->name;
                $row[] = $value->username;
                $row[] = $value->role->role_name;
                $row[] = $value->phone;
                $row[] = $value->email ? $value->email : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No Email</span>';
                $row[] = GENDER_LABEL[$value->gender];
                $row[] = permission('user-edit') ? change_status($value->id,$value->status, $value->name) : STATUS_LABEL[$value->status];
                $row[] = $value->created_by;
                $row[] = $value->modified_by ?? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Not Modified Yet</span>';
                $row[] = $value->created_at ? date(config('settings.date_format'),strtotime($value->created_at)) : '';
                $row[] = $value->modified_by ? date(config('settings.date_format'),strtotime($value->updated_at)) : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No Update Date</span>';
                $row[] = action_button($action);//custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
             $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function store_or_update_data(UserFormRequest $request)
    {
        if($request->ajax()){
            if(permission('user-add') || permission('user-edit')){
                $collection   = collect($request->validated())->except('password','password_confirmation');
                $collection   = $this->track_data($collection,$request->update_id);
                if(!empty($request->password)){
                $collection   = $collection->merge(['password'=>$request->password]);
                }
                $result       = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output       = $this->store_message($result, $request->update_id);
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('user-edit')){
                $data   = $this->model->findOrFail($request->id);
                $output = $this->data_message($data); //if data found then it will return data otherwise return error message
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function show(Request $request)
    {
        if($request->ajax()){
            if(permission('user-view')){
                $user   = $this->model->findOrFail($request->id);
                return view('user.view-data',compact('user'))->render();
            }
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('user-delete')){
                $result   = $this->model->find($request->id)->delete();
                $output   = $this->delete_message($result);
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            if(permission('user-bulk-delete')){
                $result   = $this->model->destroy($request->ids);
                $output   = $this->bulk_delete_message($result);
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function change_status(Request $request)
    {
        if($request->ajax()){
            if(permission('user-edit')){
                $result   = $this->model->find($request->id)->update(['status' => $request->status]);
                $output   = $result ? ['status' => 'success','message' => 'Status Has Been Changed Successfully']
                : ['status' => 'error','message' => 'Failed To Change Status'];
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
