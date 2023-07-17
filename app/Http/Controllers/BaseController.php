<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class BaseController extends Controller
{
    protected $model;
    protected const DELETABLE = ['1'=>'No','2'=>'Yes'];
    protected const ACTION_BUTTON = [
        'Edit' => '<i class="fas fa-edit text-primary mr-2"></i> Edit',
        'View' => '<i class="fas fa-eye text-warning mr-2"></i> View',
        'Delete' => '<i class="fas fa-trash text-danger mr-2"></i> Delete'
    ];

    /**
     * @param string $title 
     * @param string $subTitle
     * @param string $page_icon
     * @param array $breadcrumb
     */
    protected function setPageData(string $page_title, string $sub_title=null, string $page_icon=null, $breadcrumb=null)
    {
        view()->share(['page_title' => $page_title, 'sub_title' => $sub_title ?? $page_title, 'page_icon' => $page_icon, 'breadcrumb' => $breadcrumb]);
    }

    protected function table_image($path,$image,$alt_text,$gender=null)
    {
        if(!empty($path) && !empty($image) && !empty($alt_text))
        {
            return "<img src='".asset("storage/".$path.$image)."' alt='".$alt_text."' style='width:50px;'/>";
        }else{
            if($gender){
                    return "<img src='".asset("images/".($gender == 1 ? 'male' : 'female').".svg")."' alt='Default Image' style='width:50px;'/>";
            }else{
                return "<img src='".asset("images/default.svg")."' alt='Default Image' style='width:50px;'/>";
            }
            
        }
    }

    protected function set_datatable_default_properties(Request $request)
    {
        $this->model->setOrderValue($request->input('order.0.column'));
        $this->model->setDirValue($request->input('order.0.dir'));
        $this->model->setLengthValue($request->input('length'));
        $this->model->setStartValue($request->input('start'));
    }

    /**
     * @param int $errorCode
     * @param null $message
     * @return \Illuminate\Http\Response
     */
    protected function showErrorPage($errorCode = 404, $message = null)
    {
        $data['message'] = $message;
        return response()->view('errors.'.$errorCode, $data, $errorCode);
    }

    protected function response_json($status='success',$message=null,$data=null,$response_code=200)
    {
        return response()->json([
            'status'        => $status,
            'message'       => $message,
            'data'          => $data,
            'response_code' => $response_code,
        ]);
    }

    protected function datatable_draw($draw, $recordsTotal, $recordsFiltered, $data)
    {
        return array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
    }

    protected function store_message($result, $update_id = null)
    {
        return $result ? ['status' => 'success','message' => !empty($update_id) ? 'Data Has Been Updated Successfully' : 'Data Has Been Saved Successfully']
        : ['status' => 'error','message' => !empty($update_id) ? 'Failed To Update Data' : 'Failed To Save Data'];
    }

    protected function delete_message($result)
    {
        return $result ? ['status' => 'success','message' => 'Data Has Been Delete Successfully']
        : ['status' => 'error','message' => 'Failed To Delete Data'];
    }

    protected function bulk_delete_message($result)
    {
        return $result ? ['status' => 'success','message' => 'Selected Data Has Been Delete Successfully']
        : ['status' => 'error','message' => 'Failed To Delete Selected Data'];
    }

    protected function unauthorized()
    {
        return ['status'=>'error','message'=>'Unauthorized Access Blocked!'];
    }

    protected function data_message($data)
    {
        return $data ? $data : ['status' => 'error','message' => 'No data found'];
    }

    protected function access_blocked()
    {
        return redirect('unauthorized')->with(['status'=>'error','message'=>'Unauthorized Access Blocked']);
    }

    protected function track_data($collection, $update_id=null)
    {
        $created_by   = $modified_by = auth()->user()->name;
        $created_at   = $updated_at  = Carbon::now();   
        return $update_id ? $collection->merge(compact('modified_by','updated_at')) : $collection->merge(compact('created_by','created_at'));
    }

    protected function coa_head_code(string $head_name)
    {
        switch (strtolower(str_replace('-','_',$head_name))) {
            case 'assets':
                return 1;
                break;
            case 'non_current_asset':
                return 101;
                break;
            case 'inventory':
                return 10101;
                break;
            case 'current_asset':
                return 102;
                break;
            case 'cash_&_cash_equivalent':
                return 10201;
                break;
            case 'cash_in_hand':
                return 1020101;
                break;
            case 'cash_at_bank':
                return 1020102;
                break;
            case 'cash_at_mobile_bank':
                return 1020103;
                break;
            case 'account_receivable':
                return 10202;
                break;
            case 'customer_receivable':
                return 10202010001;
                break;
            case '1_walking_customer':
                return 10202010001;
                break;
            case 'loan_receivable':
                return 1020202;
                break;
            case 'equity':
                return 2;
                break;
            case 'income':
                return 3;
                break;
            case 'product_sale':
                return 301;
                break;
            case 'service_income':
                return 302;
                break;
            case 'expense':
                return 4;
                break;
            case 'default_expense':
                return 401;
                break;
            case 'material_purchase':
                return 402;
                break;
            case 'employee_salary':
                return 403;
                break;
            case 'machine_purchase':
                return 404;
                break;
            case 'maintenance_service':
                return 405;
                break;
            case 'liabilities':
                return 5;
                break;
            case 'non_current_liabilities':
                return 501;
                break;
            case 'current_liabilities':
                return 502;
                break;
            case 'account_payable':
                return 50201;
                break;
            case 'default_supplier':
                return 5020101;
                break;
            case 'employee_ledger':
                return 50202;
                break;
            case 'tax':
                return 50203;
                break;
        }
    }

    
}
