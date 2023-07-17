<?php

namespace App\Models;

use App\Models\BaseModel;

class Role extends BaseModel
{

    protected $fillable = ['role_name','deletable'];


    /******************************************
     * * * Begin :: Custom Datatable Code * * *
    *******************************************/
    //custom search column property
    protected $role_name;

    //methods to set custom search property value
    public function setRoleName($role_name)
    {
        $this->role_name = $role_name;
    }

    private function get_datatable_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)
        if (permission('role-bulk-delete')){
            $this->column_order = [null,'id','role_name','deletable',null];
        }else{
            $this->column_order = ['id','role_name','deletable',null];
        }
        
        $query = self::toBase()->where('id','!=',1);

        //search query
        if (!empty($this->role_name)) {
            $query->where('role_name', 'like', '%' . $this->role_name . '%');
        }

        //order by data fetching code
        if (isset($this->orderValue) && isset($this->dirValue)) {//orderValue is the index number of table header and dirValue is asc or desc
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);//fetch data order by matching column
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
        return self::toBase()->where('id','!=',1)->get()->count();
    }
    /******************************************
     * * * End :: Custom Datatable Code * * *
    *******************************************/

    /***************************************
     * * * Begin :: Model Relationship * * *
    ****************************************/
    public function module_role(){
        return $this->belongsToMany(Module::class)->withTimestamps();
    }

    public function permission_role(){
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
    /***************************************
     * * * End :: Model Relationship * * *
    ****************************************/
}
