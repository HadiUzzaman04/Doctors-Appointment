<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TypiCMS\NestableTrait;

class Module extends Model
{
    use HasFactory, NestableTrait;

    protected $fillable = ['menu_id', 'type', 'module_name', 'divider_title', 'icon_class', 'url', 'order', 'parent_id', 'target'];
    

    public function menu(){
        return $this->belongsTo(Menu::class);
    }

    public function parent(){
        return $this->belongsTo(Module::class,'parent_id','id')->orderBy('order','asc');
    }

    public function children(){
        $query = $this->hasMany(Module::class,'parent_id','id');

        if(auth()->user()->role_id != 1){
            $role_id = auth()->user()->role_id;
            $query->whereHas('module_role', function($q) use ($role_id){
                $q->where('role_id',$role_id);
            });
        }
        return $query->orderBy('order','asc');
    }

    public function submenu(){
        return $this->hasMany(Module::class,'parent_id','id')
        ->orderBy('order','asc')
        ->with('permission:id,module_id,name');
    }

    public function permission(){
        return $this->hasMany(Permission::class);
    }

    public function module_role() {
        return $this->hasMany(ModuleRole::class);
    }

    public static function module_list(int $menu_id){
        $modules = self::orderBy('order','asc')
        ->where(['type'=>2,'menu_id'=>$menu_id])
        ->get()
        ->nest()
        ->setIndent('-- ')
        ->listsFlattened('module_name');

        return $modules;
    }

    public static function child_module_list(int $menu_id){
        $modules = self::orderBy('order','asc')
        ->where(['type'=>2,'menu_id'=>$menu_id])
        ->doesntHave('children')
        ->get()
        ->setIndent('-- ')
        ->listsFlattened('module_name');

        return $modules;
    }


    public static function permission_module_list(int $menu_id){
       return self::doesntHave('parent')
                ->select('id','type','divider_title','module_name','order','icon_class')
                ->orderBy('order','asc')
                ->with('permission:id,module_id,name','submenu:id,parent_id,module_name,icon_class')
                ->where('menu_id',$menu_id)
                ->whereNotIn('id',[8,9,127])
                ->get();
    }
}
