<?php

namespace App\Http\Controllers\API\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\APIController;

class ReportController extends APIController
{
    public function daily_summary_report(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
        try{
            $warehouse_id = $request->get('warehouse_id') ? $request->get('warehouse_id') : 1;
            $start_date = $end_date = date('Y-m-d');

                
            $material_purchase_data = DB::table('purchases')
                                ->select(DB::raw("SUM(grand_total) as material_purchase_grand_value"))
                                ->where('warehouse_id',$warehouse_id)
                                ->whereDate('purchase_date','>=',$start_date)
                                ->whereDate('purchase_date','<=',$end_date)
                                ->first();

            $product_sale_data = DB::table('sales')
                                ->select(DB::raw("SUM(grand_total) as product_sales_grand_value"),
                                DB::raw("SUM(paid_amount) as sales_collection_received_value"),
                                DB::raw("SUM(due_amount) as product_sales_due_value"),
                                DB::raw("SUM(order_discount) as customer_discount_grand_value"))
                                ->where('warehouse_id',$warehouse_id)
                                ->whereDate('sale_date','>=',$start_date)
                                ->whereDate('sale_date','<=',$end_date)
                                ->groupBy('warehouse_id')
                                ->first();

            $total_due_grand_values = 0;
            $total_dues = DB::table('sales')
            ->selectRaw('customer_id,due_amount,max(id) as last_due_id')
            ->groupBy('customer_id')
            ->where([['warehouse_id',$warehouse_id],['due_amount','>',0]])
            ->whereDate('sale_date','>=',$start_date)
            ->whereDate('sale_date','<=',$end_date)
            ->get();
            if(!$total_dues->isEmpty())
            {
                foreach ($total_dues->chunk(10) as $chunk) {
                    foreach ($chunk as $value)
                    {
                        $total_due_grand_values += $value->due_amount;
                    }
                }
            }
            
            $total_return_value = DB::table('sale_returns')
                                ->where('warehouse_id',$warehouse_id)
                                ->whereDate('return_date','>=',$start_date)
                                ->whereDate('return_date','<=',$end_date)
                                ->sum('grand_total');
            $total_damage_value = DB::table('damages')
                                ->where('warehouse_id',$warehouse_id)
                                ->whereDate('damage_date','>=',$start_date)
                                ->whereDate('damage_date','<=',$end_date)
                                ->sum('grand_total');


            $warehouse_expense = DB::table('expenses')
                                ->where('warehouse_id',$warehouse_id)
                                ->whereDate('date','>=',$start_date)
                                ->whereDate('date','<=',$end_date)
                                ->sum('amount');

            $sr_commission_due= DB::table('transactions as t')
            ->join('chart_of_accounts as coa','t.chart_of_account_id','=','coa.id')
            ->select(DB::raw("(SUM(t.credit) - SUM(t.debit)) as due_commission"))
            ->whereNotNull('coa.salesmen_id')
            ->where('t.warehouse_id',$warehouse_id)
            ->whereDate('t.voucher_date','<=',$end_date)
            ->first();

            $supplier_due = DB::table('transactions as t')
            ->join('chart_of_accounts as coa','t.chart_of_account_id','=','coa.id')
            ->select(DB::raw("(SUM(t.credit) - SUM(t.debit)) as due"))
            ->whereNotNull('coa.supplier_id')
            ->where('t.warehouse_id',$warehouse_id)
            ->whereDate('t.voucher_date','<=',$end_date)
            ->first();
            
            
            $data = [
                'total_purchase_amount'          => $material_purchase_data->material_purchase_grand_value ? number_format($material_purchase_data->material_purchase_grand_value,2,'.','') : number_format(0,2,'.',''),
                'total_supplier_due_amount'      => $supplier_due ? number_format($supplier_due->due,2,'.','') : number_format(0,2,'.',''),
                'total_sale_amount'              => $product_sale_data ?  number_format($product_sale_data->product_sales_grand_value,2,'.','') : number_format(0,2,'.',''),
                'total_collection_amount'        => $product_sale_data ?  number_format($product_sale_data->sales_collection_received_value,2,'.','') : number_format(0,2,'.',''),
                'total_sale_due_amount'          => $product_sale_data ?  number_format($product_sale_data->product_sales_due_value,2,'.','') : number_format(0,2,'.',''),
                'total_discount_amount'          => $product_sale_data ?  number_format($product_sale_data->customer_discount_grand_value,2,'.','') : number_format(0,2,'.',''),
                'total_customer_due_amount'      => number_format($total_due_grand_values,2,'.',''),
                'total_sr_commission_due_amount' => $sr_commission_due ? number_format($sr_commission_due->due_commission,2,'.','') : number_format(0,2,'.',''),
                'total_product_damage_amount'    => number_format(($total_damage_value + $total_return_value),2,'.',''),
                'total_expense_amount'           => number_format($warehouse_expense,2,'.',''),
            ];
        } catch (Exception $e) {
            $status  = false;
            $message = $e->getMessage();
        }
        return $this->sendResult($message,$data,$errors,$status);
    }

    public function material_stock_report(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
        try{
            $material_id = $request->get('material_id');
            $materials = DB::table('materials as m')
            ->leftJoin('units as u','m.unit_id','=','u.id')
            ->select('m.id','m.material_name','m.material_code','m.qty','u.unit_name','m.cost')
            ->when($material_id,function($q) use ($material_id){
                $q->where('m.id',$material_id);
            })
            ->paginate(10);
            if(!$materials->isEmpty())
            {
                $data = $materials;
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

    public function product_stock_report(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
        try{
            $warehouse_id = $request->get('warehouse_id');
            
            $product_id = $request->get('product_id');
            $products = DB::table('products as p')
            ->leftJoin('warehouse_product as wp','p.id','=','wp.product_id')
            ->leftJoin('units as u','p.base_unit_id','=','u.id')
            ->select('p.id','p.name','p.code','wp.qty','u.unit_name','p.base_unit_price as price')
            ->when($warehouse_id,function($q) use ($warehouse_id){
                $q->where('wp.warehouse_id',$warehouse_id);
            })
            ->when($product_id,function($q) use ($product_id){
                $q->where('wp.product_id',$product_id);
            })
            ->paginate(10);
            if(!$products->isEmpty())
            {
                $data = $products;
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
}
