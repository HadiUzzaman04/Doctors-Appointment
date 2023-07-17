<?php

namespace App\Models;


use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;


    protected $fillable = [
        'role_id','name','username','email','phone','avatar','gender','password','status','deletable','created_by','modified_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',  
    ];

    
    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /***************************************
     * * * Begin :: Model Relationship * * *
    ****************************************/
    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    } 
     /***************************************
     * * * End :: Model Relationship * * *
    ****************************************/

    /******************************************
     * * * Begin :: Custom Datatable Code * * *
    *******************************************/
    protected $order = ['id' => 'desc'];
    protected $column_order;

    protected $orderValue;
    protected $dirValue;
    protected $startVlaue;
    protected $lengthVlaue;

    public function setOrderValue($orderValue)
    {
        $this->orderValue = $orderValue;
    }
    public function setDirValue($dirValue)
    {
        $this->dirValue = $dirValue;
    }
    public function setStartValue($startVlaue)
    {
        $this->startVlaue = $startVlaue;
    }
    public function setLengthValue($lengthVlaue)
    {
        $this->lengthVlaue = $lengthVlaue;
    }

    protected $name;
    protected $username;
    protected $phone;
    protected $email;
    protected $role_id;
    protected $status;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setRoleID($role_id)
    {
        $this->role_id = $role_id;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }


    private function get_datatable_query()
    { 
        if (permission('user-bulk-delete')){
            $this->column_order = [null,'id','id','name','username','role_id','phone','email','gender','status','created_by','modified_by','created_at','updated_at',null];
        }else{
            $this->column_order = ['id','id','name','username','role_id','phone','email','gender','status','created_by','modified_by','created_at','updated_at',null];
        }
        

        $query = self::with('role:id,role_name')->where('id','!=',1);

        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->username)) {
            $query->where('username', 'like', '%' . $this->username . '%');
        }
        if (!empty($this->phone)) {
            $query->where('phone', 'like', '%' . $this->phone . '%');
        }
        if (!empty($this->email)) {
            $query->where('email', 'like', '%' . $this->email . '%');
        }
        if (!empty($this->role_id)) {
            $query->where('role_id', $this->role_id );
        }
        if (!empty($this->status)) {
            $query->where('status', $this->status );
        }

        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
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

}
