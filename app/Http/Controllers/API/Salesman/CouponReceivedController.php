<?php
namespace App\Http\Controllers\API\Salesman;

use Exception;
use Illuminate\Http\Request;
use App\Models\ReceivedCoupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\APIController;
use Modules\Production\Entities\ProductionCoupon;

class CouponReceivedController extends APIController
{

    public function store_received_coupon(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;

        $validator = Validator::make($request->all(), [
            'coupon_id'   => 'required|integer',
            'coupon_code' => 'required|string|min:10|max:10',
            'customer_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $status  = false;
            $errors  = $validator->errors();
            $message = "Validation Errors";
            return $this->sendResult($message,$data,$errors,$status);
        }else{
            DB::beginTransaction();
            try {
                $coupon = ProductionCoupon::where(['id' => $request->coupon_id,'status' => 2])->first();
                if($coupon){
                    $received = ReceivedCoupon::create([
                        'salesmen_id'  => auth()->user()->id,
                        'customer_id'  => $request->customer_id,
                        'coupon_id'    => $request->coupon_id,
                    ]);
                    if($received)
                    {
                        $coupon->update(['status' => 1]);
                        $status  = true;
                        $message = "Coupon Data Stored Successfully";
                    }else{
                        $status  = false;
                        $message = "Failed To Store Coupon Data";
                    }
                }else{
                    $status  = false;
                    $message = "Invalid Coupon";
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $status = false;
                $message = $e->getMessage();
            }
            return $this->sendResult($message,$data,$errors,$status);
        }
    }

}
