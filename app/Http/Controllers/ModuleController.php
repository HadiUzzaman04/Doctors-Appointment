<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\BaseController;
use App\Http\Requests\ModuleFormRequest;

class ModuleController extends BaseController
{
    public function __construct(Module $model)
    {
        $this->model = $model;
    }

    public function index(Menu $menu)
    {
        if(permission('menu-builder-access')){
            $this->setPageData('Menu Builder','Menu Builder','fas fa-th-list',[['name' => 'Menu','link'=> route('menu')],['name' => 'Menu Builder']]);
            return view('module.index',compact('menu'));
        }else{
            return $this->access_blocked();
        }
    }

    public function storeOrUpdate(ModuleFormRequest $request)
    {
        if($request->ajax()){
            if(permission('menu-module-add') || permission('menu-module-edit')){
                $collection   = collect($request->validated());
                $menu_id      = $request->menu_id;
                $target       = $request->target ? $request->target : null; 
                $created_at   = $updated_at = Carbon::now();
                $collection   = !empty($request->update_id) ? $collection->merge(compact('target','updated_at')) : $collection->merge(compact('menu_id','target','created_at'));
                $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());

                $output       = $result ? ['status' => 'success','message' => !empty($request->update_id) ? 'Data Has Been Updated Successfully' : 'Data Has Been Saved Successfully']
                    : ['status' => 'error','message' => !empty($request->update_id) ? 'Failed To Update Data' : 'Failed To Save Data'];

                if (auth()->user()->role_id == 1) {
                    $this->restore_session_menu();
                }
                
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
        if ($request->ajax()) {
            if(permission('menu-module-edit')){
                $data   = $this->model->findOrFail($request->id);
                $output = $this->data_message($data); //if data found then it will return data otherwise return error message
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json(
                $this->unauthorized());
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if(permission('menu-module-delete')){
                $module   = $this->model->with('permission')->findOrFail($request->id);
                if(!$module->permission->isEmpty())
                {
                    $module->permission()->delete();
                }
                $result = $module->delete();
                $output = $this->delete_message($result);
                if (auth()->user()->role_id == 1) {
                    $this->restore_session_menu();
                }
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function get_menu_modules(Request $request)
    {
        if($request->ajax()){
            $menu = Menu::with('menuItems')->find($request->id);
            return view('module.module-list',compact('menu'))->render();
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function orderItem(Request $request){
        $menuItemOrder = json_decode($request->input('order'));
        $this->orderMenu($menuItemOrder, null);
        if (auth()->user()->role_id == 1) {
            $this->restore_session_menu();
        }
    }

    
    protected function orderMenu(array $menuItems, $parent_id)
    {
        foreach ($menuItems as $index => $menuItem) {
            $item               = $this->model->findOrFail($menuItem->id);
            $item->order        = $index + 1;
            $item->parent_id    = $parent_id;
            $item->save();
            if(isset($menuItem->children)){
                $this->orderMenu($menuItem->children, $item->id);
            }
        }
    }

    private function restore_session_menu(){
        $menus = $this->model->doesntHave('parent')
            ->orderBy('order','asc')
            ->with('children')
            ->where('menu_id',1)
            ->get(); //permitted module list
        if(!$menus->isEmpty()){
            Session::forget('user_menu');
            Session::put('user_menu',$menus); //permitted modules putted into session
        }
    }


}
