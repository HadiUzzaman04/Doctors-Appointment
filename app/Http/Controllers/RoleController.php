<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Module;
use App\Models\ModuleRole;
use Illuminate\Http\Request;
use App\Models\PermissionRole;
use App\Http\Requests\RoleFormRequest;

class RoleController extends BaseController
{
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('role-access')){
            $this->setPageData('Role','Role Manage','fas fa-bars',[['name' => 'Role']]);
            $deletable = self::DELETABLE;
            return view('role.index',compact('deletable'));
        }else{
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if($request->ajax()){
            if (!empty($request->role_name)) {
                $this->model->setRoleName($request->role_name);
            }

            $this->set_datatable_default_properties($request);//set datatable default properties
            $list = $this->model->getDatatableList();//get table data
            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('role-edit')){
                    $action .= ' <a class="dropdown-item" href="'.route('role.edit',['id'=>$value->id]).'">'.self::ACTION_BUTTON['Edit'].'</a>';
                }
                if(permission('role-view')){
                    $action .= ' <a class="dropdown-item" href="'.route('role.view',['id'=>$value->id]).'">'.self::ACTION_BUTTON['View'].'</a>';
                }
                if(permission('role-delete')){
                    if($value->deletable == 2){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->role_name . '">'.self::ACTION_BUTTON['Delete'].'</a>';
                    }
                }

                $row = [];
                if(permission('role-bulk-delete')){
                    $row[] = ($value->deletable == 2) ? row_checkbox($value->id) : '';//custom helper function to show the table each row checkbox
                }
                $row[] = $no;
                $row[] = $value->role_name;
                $row[] = self::DELETABLE[$value->deletable];
                $row[] = action_button($action);//custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
             $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function create()
    {
        if(permission('role-add')){
            $this->setPageData('Role Create','Role Create','fas fa-bars',[['name' => 'Role','link' => route('role')],['name' => 'Role Create']]);
            $permission_modules = Module::permission_module_list(1);
            return view('role.form',compact('permission_modules'));
        }else{
            return $this->access_blocked();
        }
    }

    public function store_or_update_data(RoleFormRequest $request)
    {
        if($request->ajax()){
            if(permission('role-add') || permission('role-edit')){
                $collection = collect($request->validated());
                $role       = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                if($role){
                    $role->module_role()->sync($request->module);
                    $role->permission_role()->sync($request->permission);
                }
                $output = $this->store_message($role, $request->update_id);
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function edit(int $id)
    {
        if(permission('role-edit')){
            $this->setPageData('Role Edit','Role Edit','fas fa-bars',[['name' => 'Role','link' => route('role')],['name' => 'Role Edit']]);
            $role = $this->model->with('module_role','permission_role')->find($id);
            $role_module = [];
            if(!$role->module_role->isEmpty())
            {
                foreach ($role->module_role as $value) {
                    array_push($role_module,$value->id);
                }
            }
            $role_permission = [];
            if(!$role->permission_role->isEmpty())
            {
                foreach ($role->permission_role as $value) {
                    array_push($role_permission,$value->id);
                }
            }

            $data = [
                'role'            => $role,
                'role_module'     => $role_module,
                'role_permission' => $role_permission,
                'permission_modules' => Module::permission_module_list(1)
            ];
            
            return view('role.form',$data);
        }else{
            return $this->access_blocked();
        }
    }

    public function show(int $id)
    {
        if(permission('role-view')){
            $this->setPageData('Role Details','Role Details','fas fa-bars',[['name' => 'Role','link' => route('role')],['name' => 'Role Details']]);
            $role = $this->model->with('module_role','permission_role')->find($id);
            $role_module = [];
            if(!$role->module_role->isEmpty())
            {
                foreach ($role->module_role as $value) {
                    array_push($role_module,$value->id);
                }
            }
            $role_permission = [];
            if(!$role->permission_role->isEmpty())
            {
                foreach ($role->permission_role as $value) {
                    array_push($role_permission,$value->id);
                }
            }

            $data = [
                'role'            => $role,
                'role_module'     => $role_module,
                'role_permission' => $role_permission,
                'permission_modules' => Module::permission_module_list(1)
            ];
            return view('role.view',$data);
        }else{
            return $this->access_blocked();
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('role-delete')){
                $role = $this->model->with('users','module_role','permission_role')->find($request->id);
                if(!$role->users->isEmpty()){
                    $output = ['status'=>'error','message'=>'Data Can\'t Delete Because It\'s Related With Others Data'];
                }else{
                    if(!$role->module_role->isEmpty()){
                        $delete_module_role     = $role->module_role()->detach();
                    }
                    if(!$role->permission_role->isEmpty()){
                        $delete_permission_role = $role->permission_role()->detach();   
                    }             
                    $result = $role->delete();
                    $output = $this->delete_message($result);
                    
                }
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
            if(permission('role-bulk-delete')){
                $delete_list = [];
                $undelete_list = [];
                foreach ($request->ids as $id) {
                    $role = $this->model->with('users')->find($id);
                    if(!$role->users->isEmpty()){
                        array_push($undelete_list,$role->role_name);
                    }else{
                        array_push($delete_list,$id);
                    }
                } 
                if(!empty($delete_list)){
                    $delete_module_role     = ModuleRole::whereIn('role_id',$delete_list)->delete();
                    $delete_permission_role = PermissionRole::whereIn('role_id',$delete_list)->delete();
                    $result = $role->destroy($delete_list);
                    $output = $result ?  ['status'=>'success','message'=> 'Selected Data Has Been Deleted Successfully. '. (!empty($undelete_list) ? 'Except these roles('.implode(',',$undelete_list).')'.' because they are related with others data.' : '')] 
                    : ['status'=>'error','message'=>'Failed To Delete Data.'];
                    
                }else{
                    $output = ['status'=>'error','message'=> !empty($undelete_list) ? 'These roles('.implode(',',$undelete_list).')'.' 
                    can\'t delete because they are related with others data.' : ''];
                }
            }else{
                $output    = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
