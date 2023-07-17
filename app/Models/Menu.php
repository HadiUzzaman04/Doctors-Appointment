<?php
namespace App\Models;

use App\Models\BaseModel;

class Menu extends BaseModel
{
    protected $fillable = ['menu_name','deletable']; //fillable column name

    /******************************************
     * * * Begin :: Custom Datatable Code * * *
    *******************************************/
    //custom search column property
    protected $menu_name; 

    //methods to set custom search property value
    public function setMenuName($menu_name)
    {
        $this->menu_name = $menu_name;
    }


    private function get_datatable_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)
        if (permission('menu-bulk-delete')){
            $this->column_order = [null,'id','menu_name','deletable',null];
        }else{
            $this->column_order = ['id','menu_name','deletable',null];
        }
        
        $query = self::toBase();

        //search query
        if (!empty($this->menu_name)) {
            $query->where('menu_name', 'like', '%' . $this->menu_name . '%');
        }

        //order by data fetching code
        if (isset($this->orderValue) && isset($this->dirValue)) { //orderValue is the index number of table header and dirValue is asc or desc
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue); //fetch data order by matching column
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }

    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }

    public function count_all()
    {
        return self::toBase()->get()->count();
    }
    /******************************************
     * * * End :: Custom Datatable Code * * *
    *******************************************/


    /***************************************
     * * * Begin :: Model Relationship * * *
    ****************************************/
    public function menuItems()
    {
        return $this->hasMany(Module::class)->doesntHave('parent')->orderBy('order','asc');
    }
    /***************************************
     * * * End :: Model Relationship * * *
    ****************************************/

}
