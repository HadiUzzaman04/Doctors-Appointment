<?php

namespace Modules\Backend\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Backend\Entities\Department;

class DepartmentController extends BaseController
{
    public function department()
    {
        $setTitle = __('Department');
        $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
        $data = Department::all();
        return view('backend::department', compact('data'));
    }
    public function create(Request $request)
    {
        $collection = collect(['name' => $request->name,'details' => $request->details]);
        Department::create($collection->all());
        return redirect()->back();
    }
    public function delete($id)
    {
        Department::find($id)->delete();
        return redirect()->back();
    }
    public function edit($id)
    {
        $setTitle = __('Edit Department');
        $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
        $data = Department::find($id);
        return view('backend::edit.editdepartment', compact('data'));
    }
    public function update(Request $request, $id)
    {
        $data = Department::find($id);
        $data->name = $request->name;
        $data->details = $request->details;
        $data->update();
        return redirect()->route('admin.department');
    }
}
