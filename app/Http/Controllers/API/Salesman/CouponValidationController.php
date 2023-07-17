<?php

namespace App\Http\Controllers\API\Salesman;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\APIController;

class CouponValidationController extends APIController
{

    public function index(Request $request)
    {
        // qrcode = 'ID:1;CODE:64T1OSYJO2';
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
        $qrcode_text = $request->qrcode;
        if($qrcode_text){
            $id   = explode(':',explode(';',$qrcode_text)[0])[1];
            $code = explode(':',explode(';',$qrcode_text)[1])[1];
            $production_coupon = DB::table('production_coupons as pc')
            ->join('production_products as pp','pc.production_product_id','=','pp.id')
            ->join('productions as pro','pp.production_id','=','pro.id')
            ->join('products as p','pp.product_id','=','p.id')
            ->where([
                'pc.id'                 => $id,
                'pc.coupon_code'        => $code,
                'pro.warehouse_id'      => auth()->user()->warehouse_id,
                'pro.status'            => 1,
                'pro.production_status' => 3,
                'pro.transfer_status'   => 2
            ])
            ->selectRaw('pc.*,pp.coupon_price,pp.coupon_exp_date,p.name,p.image')
            ->first();

            if($production_coupon)
            {
                if($production_coupon->status == 2)
                {
                    if($production_coupon->coupon_exp_date < date('Y-m-d'))
                    {
                        $status  = false;
                        $message = "This Coupon Code Has Expired!";
                    }else{
                        $status  = true;
                        $data = [
                            'batch_no'        => $production_coupon->batch_no,
                            'coupon_id'       => $production_coupon->id,
                            'coupon_code'     => $production_coupon->coupon_code,
                            'coupon_amount'   => number_format($production_coupon->coupon_price,2,'.',''),
                            'coupon_exp_date' => date('d-M-Y',strtotime($production_coupon->coupon_exp_date)),
                            'product_name'    => $production_coupon->name,
                            'product_image'   => $production_coupon->image ? asset('storage/'.PRODUCT_IMAGE_PATH.$production_coupon->image) : asset('images/product.svg'),
                        ];
                        $message = "This Coupon Code is Valid";
                    }
                }else{
                    $status  = false;
                    $message = "This Coupon Code is Already Used!";
                }
            }else{
                $status  = false;
                $message = "This Coupon Code is Invalid!";
            }
        }else{
            $status  = false;
            $message = "Invalid QR Code!";
        }
        return $this->sendResult($message,$data,$errors,$status);
    }

}
