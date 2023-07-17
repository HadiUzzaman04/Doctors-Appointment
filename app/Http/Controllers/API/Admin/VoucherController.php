<?php

namespace App\Http\Controllers\API\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\APIController;
use Modules\Account\Entities\VoucherApproval;

class VoucherController extends APIController
{
    public function index(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
        try{
            $warehouse_id = $request->get('warehouse_id');

           $vouchers = DB::table('transactions as t')
            ->leftjoin('warehouses as w','t.warehouse_id','=','w.id')
            ->selectRaw("t.voucher_no,t.voucher_date,t.description,t.approve,sum(t.credit) as credit,sum(t.debit) as debit,w.name as warehouse_name")
            ->whereIn('t.voucher_type',['DV','CV','CONTRAV','JOURNALV'])
            ->where('t.approve',3)
            ->when($warehouse_id,function($q) use ($warehouse_id){
                $q->where('t.warehouse_id',$warehouse_id);
            })
            ->groupBy('t.voucher_no')
            ->paginate(10);
            if(!$vouchers->isEmpty())
            {
                $data = $vouchers;
            }else{
                $status = false;
                $message = 'No data found!';
            }
        }catch (Exception $e) {
            $status  = false;
            $message = $e->getMessage();
        }
        return $this->sendResult($message,$data,$errors,$status);
    }

    public function destroy(string $voucher_no)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
        
        if($voucher_no){
            try{
                $result  = VoucherApproval::where('voucher_no',$voucher_no)->delete();
                if($result){
                    $message = ['Voucher deleted successfully'];
                }else{
                    $status  = false;
                    $message = ['Failed to delete voucher'];
                }
            }catch (Exception $e) {
                $status  = false;
                $message = $e->getMessage();
            }
        }else{
            $status  = false;
            $message = ['Voucher No. is required'];
        }
        
        return $this->sendResult($message,$data,$errors,$status);
    }

    public function voucher_approve(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
        
        if($request->voucher_no){
            try{
                $result  = VoucherApproval::where('voucher_no',$request->voucher_no)->update(['approve' => $request->status]);
                if($result){
                    $message = ['Voucher approval status updated successfully'];
                }else{
                    $status  = false;
                    $message = ['Failed to update voucher approval status'];
                }
                
            }catch (Exception $e) {
                $status  = false;
                $message = $e->getMessage();
            }
        }else{
            $status  = false;
            $message = ['Voucher No. is required'];
        }
        return $this->sendResult($message,$data,$errors,$status);
    }
}
