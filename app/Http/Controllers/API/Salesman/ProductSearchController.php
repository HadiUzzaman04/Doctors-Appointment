<?php

namespace App\Http\Controllers\API\Salesman;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\APIController;

class ProductSearchController extends APIController
{
    public function products()
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
            $products = DB::table('warehouse_product as wp')
            ->join('products as p','wp.product_id','=','p.id')
            ->leftjoin('taxes as t','p.tax_id','=','t.id')
            ->leftjoin('units as u','p.base_unit_id','=','u.id')
            ->selectRaw('wp.*,p.name,p.code,p.image,p.base_unit_id,p.base_unit_price as price,p.tax_method,t.name as tax_name,t.rate as tax_rate,u.unit_name,u.unit_code')
            ->where([['wp.warehouse_id',auth()->user()->warehouse_id],['wp.qty','>',0]])
            ->orderBy('p.name','asc')
            ->get();
            
            if(!$products->isEmpty())
            {
                foreach($products as $row)
                {
                    $temp_array       = array();
                    $temp_array['id']             = $row->id;
                    $temp_array['name']           = $row->name;
                    $temp_array['code']           = $row->code;
                    $temp_array['base_unit_id']   = $row->base_unit_id;
                    $temp_array['base_unit_name'] = $row->unit_name;
                    $temp_array['net_unit_price'] = number_format($row->price,'2','.','');
                    $temp_array['stock_qty']      = $row->qty;
                    $temp_array['tax_name']       = $row->tax_name ? $row->tax_name : 'No Tax';
                    $temp_array['tax_rate']       = $row->tax_rate ? number_format($row->tax_rate,2,'.','') : number_format(0,2,'.','');
                    $temp_array['tax_method']     = $row->tax_method;
                    $temp_array['image']          = $row->image ? asset('storage/'.PRODUCT_IMAGE_PATH.$row->image) : asset('images/product.svg');
                    $data[] = $temp_array;
                }
            }else{
                $message = 'No data found!';
                $status = false;
            }

        return $this->sendResult($message,$data,$errors,$status);
    }

    public function product_data(int $id)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;

        $product = DB::table('warehouse_product as wp')
        ->join('products as p','wp.product_id','=','p.id')
        ->leftjoin('taxes as t','p.tax_id','=','t.id')
        ->leftjoin('units as u','p.base_unit_id','=','u.id')
        ->where([
            ['wp.warehouse_id',auth()->user()->warehouse_id],
            ['wp.product_id',$id]
        ])
        ->selectRaw('wp.*,p.name,p.code,p.base_unit_id,p.base_unit_price as price,p.tax_method,t.name as tax_name,t.rate as tax_rate,u.unit_name,u.unit_code')
        ->first();

        if($product)
        {
            $data['id']             = $product->product_id;
            $data['name']           = $product->name;
            $data['code']           = $product->code;
            $data['base_unit_id']   = $product->base_unit_id;
            $data['base_unit_name'] = $product->unit_name;
            $data['net_unit_price'] = number_format($product->price,'2','.','');
            $data['stock_qty']      = $product->qty;
            $data['tax_name']       = $product->tax_name ? $product->tax_name : 'No Tax';
            $data['tax_rate']       = $product->tax_rate ? number_format($product->tax_rate,2,'.','') : number_format(0,2,'.','');
            $data['tax_method']     = $product->tax_method;            //1=Exclude,2=Include
        }else{
            $message = 'No data found!';
            $status = false;
        }
        return $this->sendResult($message,$data,$errors,$status);
    }
}
