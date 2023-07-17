<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Requests\MenuFormRequest;
use App\Http\Controllers\BaseController;

class MenuController extends BaseController
{
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (permission('menu-access')){
            $this->setPageData('Menu','Menu','fas fa-bars',[['name' => 'Menu']]);
            $deletable = self::DELETABLE;
            return view('menu.index',compact('deletable'));
        }else{
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if($request->ajax()){
            if (!empty($request->menu_name)) {
                $this->model->setMenuName($request->menu_name);
            }

            $this->set_datatable_default_properties($request);//set datatable default properties
            $list = $this->model->getDatatableList();//get table data
            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('menu-builder-access')){
                    $action .= ' <a class="dropdown-item" href="'.route("menu.builder",["menu"=>$value->id]).'"><i class="fas fa-th-list text-success mr-2"></i> Builder</a>';
                }
                if(permission('menu-edit')){
                $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.self::ACTION_BUTTON['Edit'].'</a>';
                }
                if(permission('menu-delete')){
                    if($value->deletable == 2){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->menu_name . '">'.self::ACTION_BUTTON['Delete'].'</a>';
                    }
                }

                $row = [];
                if(permission('menu-bulk-delete')){
                    $row[] = ($value->deletable == 2) ? row_checkbox($value->id) : '';//custom helper function to show the table each row checkbox
                }
                $row[] = $no;
                $row[] = $value->menu_name;
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

    public function store_or_update_data(MenuFormRequest $request)
    {
        if($request->ajax()){
            if (permission('menu-add') || permission('menu-edit')){
                $collection   = collect($request->validated());
                $created_at   = $updated_at = Carbon::now();
                $collection   = !empty($request->update_id) ? $collection->merge(compact('updated_at')) : $collection->merge(compact('created_at'));
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
            if(permission('menu-edit')){
                $data   = $this->model->findOrFail($request->id);
                $output = $this->data_message($data); //if data found then it will return data otherwise return error message
            }else{
                $output = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('menu-delete')){
                $menu = $this->model->with('menuItems')->find($request->id);
                if (!$menu->menuItems->isEmpty()) {
                    $output = ['status'=>'error','message'=>'This menu can\'t delete beacuse it\'s related with others data'];
                } else {
                    $result = $menu->delete();
                    $output = $this->delete_message($result);
                }
            }else{
                $output = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            if(permission('menu-bulk-delete')){
                $delete_list = [];
                $undelete_list = [];
                foreach ($request->ids as $id) {
                    $menu = $this->model->with('menuItems')->find($id);
                    if(!$menu->menuItems->isEmpty()){
                        array_push($undelete_list,$menu->menu_name);
                    }else{
                        array_push($delete_list,$id);
                    }
                } 
                if(!empty($delete_list)){
                        $result = $menu->destroy($delete_list);
                        $output = $result ?  ['status'=>'success','message'=> 'Selected Data Has Been Deleted Successfully. '. (!empty($undelete_list) ? 'Except these menus('.implode(',',$undelete_list).')'.' because they are associated with others data.' : '')] 
                        : ['status'=>'error','message'=>'Failed To Delete Data.'];
                }else{
                    $output = ['status'=>'error','message'=> !empty($undelete_list) ? 'These menus('.implode(',',$undelete_list).')'.' 
                    can\'t delete because they are associated with others data.' : ''];
                }
            }else{
                $output = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    
    
}

